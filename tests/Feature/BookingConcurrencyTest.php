<?php

namespace Tests\Feature;

use App\Exceptions\SlotUnavailableException;
use App\Livewire\Public\ClinicBookingWizard;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicSchedule;
use App\Services\AppointmentBookingService;
use Carbon\CarbonImmutable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingConcurrencyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_concurrent_booking_attempts_for_same_slot_results_in_single_appointment(): void
    {
        $futureMonday = CarbonImmutable::parse('2026-11-23 10:00:00');
        $this->travelTo($futureMonday->copy()->subDays(5));

        $clinic = Clinic::factory()->active()->create();

        // Grade ativa na segunda-feira das 08:00 às 12:00
        ClinicSchedule::factory()->for($clinic)->create([
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        $bookingService = app(AppointmentBookingService::class);

        // Tentativa 1: Paciente A reserva o horário das 10:00
        $appointmentA = $bookingService->book(
            clinic: $clinic,
            scheduledAt: $futureMonday,
            patientName: 'Paciente A (Primeiro)',
            patientPhone: '11911112222',
            notes: 'Primeiro a reservar',
        );

        $this->assertModelExists($appointmentA);
        $this->assertSame('Paciente A (Primeiro)', $appointmentA->patient_name);

        // Tentativa 2: Paciente B tenta reservar exatamente o mesmo slot e mesma clínica
        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('O horário selecionado não está mais disponível.');

        $bookingService->book(
            clinic: $clinic,
            scheduledAt: $futureMonday,
            patientName: 'Paciente B (Segundo)',
            patientPhone: '11933334444',
            notes: 'Tentativa concorrente que deve falhar',
        );

        // Garante que estritamente 1 registro foi criado para o horário
        $count = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinic->id)
            ->where('scheduled_at', $futureMonday)
            ->count();

        $this->assertSame(1, $count);
    }

    public function test_database_unique_index_prevents_duplicate_appointments(): void
    {
        $clinic = Clinic::factory()->active()->create();
        $slot = CarbonImmutable::parse('2026-11-23 14:00:00');

        // Primeira inserção direta no banco
        Appointment::withoutGlobalScopes()->create([
            'clinic_id' => $clinic->id,
            'patient_name' => 'Paciente Inicial',
            'patient_phone' => '11988887777',
            'scheduled_at' => $slot,
            'status' => 'pending',
        ]);

        // Segunda inserção direta com o mesmo par (clinic_id, scheduled_at)
        $this->expectException(QueryException::class);

        Appointment::withoutGlobalScopes()->create([
            'clinic_id' => $clinic->id,
            'patient_name' => 'Paciente Duplicado Ilegal',
            'patient_phone' => '11999990000',
            'scheduled_at' => $slot,
            'status' => 'pending',
        ]);
    }

    public function test_concurrent_wizard_submissions_in_livewire_prevent_double_booking(): void
    {
        $futureMonday = CarbonImmutable::parse('2026-11-30 09:30:00');
        $this->travelTo($futureMonday->copy()->subDays(5));

        $clinic = Clinic::factory()->active()->create();

        ClinicSchedule::factory()->for($clinic)->create([
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Paciente 1 abre o formulário e escolhe o horário 09:30
        $wizardPatient1 = Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->set('patient_name', 'Paciente Um')
            ->set('patient_phone', '11911111111')
            ->set('complaint', 'Avaliação dentária')
            ->set('pain_level', 2)
            ->call('goToStep3')
            ->call('goToStep4')
            ->set('selected_date', '2026-11-30')
            ->call('selectTime', '09:30');

        // Paciente 2 abre o formulário simultaneamente e escolhe o mesmo horário 09:30
        $wizardPatient2 = Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->set('patient_name', 'Paciente Dois')
            ->set('patient_phone', '11922222222')
            ->set('complaint', 'Limpeza de rotina')
            ->set('pain_level', 1)
            ->call('goToStep3')
            ->call('goToStep4')
            ->set('selected_date', '2026-11-30')
            ->call('selectTime', '09:30');

        // Paciente 1 confirma primeiro -> SUCESSO
        $wizardPatient1->call('confirmBooking')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 5);

        // Paciente 2 confirma fração de segundo depois -> ERRO DE COLISÃO
        $wizardPatient2->call('confirmBooking')
            ->assertSet('currentStep', 4)
            ->assertSet('errorMessage', 'O horário selecionado não está mais disponível.');

        // O slot '09:30' não consta mais na lista de horários livres do Paciente 2
        $availableSlots = $wizardPatient2->get('available_slots');
        $this->assertNotContains('09:30', $availableSlots);

        // Validação no banco: apenas 1 agendamento criado
        $appointments = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinic->id)
            ->where('scheduled_at', $futureMonday)
            ->get();

        $this->assertCount(1, $appointments);
        $this->assertSame('Paciente Um', $appointments->first()->patient_name);
    }

    public function test_different_clinics_booking_same_timestamp_do_not_collide(): void
    {
        $futureMonday = CarbonImmutable::parse('2026-12-07 10:00:00');
        $this->travelTo($futureMonday->copy()->subDays(5));

        $clinicA = Clinic::factory()->active()->create(['name' => 'Clínica A']);
        $clinicB = Clinic::factory()->active()->create(['name' => 'Clínica B']);

        ClinicSchedule::factory()->for($clinicA)->create([
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        ClinicSchedule::factory()->for($clinicB)->create([
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        $bookingService = app(AppointmentBookingService::class);

        // Ambas as clínicas reservam exatamente o mesmo horário simultaneamente
        $appointmentA = $bookingService->book(
            clinic: $clinicA,
            scheduledAt: $futureMonday,
            patientName: 'Paciente Clínica A',
            patientPhone: '11911111111',
        );

        $appointmentB = $bookingService->book(
            clinic: $clinicB,
            scheduledAt: $futureMonday,
            patientName: 'Paciente Clínica B',
            patientPhone: '11922222222',
        );

        $this->assertModelExists($appointmentA);
        $this->assertModelExists($appointmentB);
        $this->assertSame($clinicA->id, $appointmentA->clinic_id);
        $this->assertSame($clinicB->id, $appointmentB->clinic_id);
    }
}

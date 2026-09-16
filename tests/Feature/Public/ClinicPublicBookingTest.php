<?php

namespace Tests\Feature\Public;

use App\Livewire\Public\ClinicBookingWizard;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClinicPublicBookingTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_view_clinic_landing_page_by_slug(): void
    {
        $clinic = Clinic::factory()->active()->create([
            'name' => 'Odonto Sorriso Prime',
            'slug' => 'sorriso-prime',
            'whatsapp_number' => '11999887766',
        ]);

        $this->get('/sorriso-prime')
            ->assertOk()
            ->assertSee('Odonto Sorriso Prime')
            ->assertSeeLivewire(ClinicBookingWizard::class);
    }

    public function test_invalid_slug_returns_404(): void
    {
        $this->get('/slug-inexistente-xyz')
            ->assertNotFound();
    }

    public function test_paused_clinic_displays_paused_notice(): void
    {
        $clinic = Clinic::factory()->pastDue()->create([
            'name' => 'Clínica Inadimplente',
            'slug' => 'clinica-inadimplente',
        ]);

        $this->get('/clinica-inadimplente')
            ->assertOk()
            ->assertSee('Agendamentos Online Temporariamente Pausados')
            ->assertDontSeeLivewire(ClinicBookingWizard::class);
    }

    public function test_wizard_validates_step_1_patient_details(): void
    {
        $clinic = Clinic::factory()->active()->create();

        Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->assertSet('currentStep', 1)
            ->call('goToStep2')
            ->assertHasErrors(['patient_name', 'patient_phone'])
            ->set('patient_name', 'Carlos Drummond')
            ->set('patient_phone', '11988887777')
            ->call('goToStep2')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 2);
    }

    public function test_wizard_validates_and_processes_triage_in_step_2(): void
    {
        $clinic = Clinic::factory()->active()->create([
            'ai_provider' => 'none',
        ]);

        $component = Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->set('patient_name', 'Carlos Drummond')
            ->set('patient_phone', '11988887777')
            ->set('currentStep', 2)
            ->call('goToStep3')
            ->assertHasErrors(['complaint'])
            ->set('complaint', 'Dor severa no dente molar após queda na calçada')
            ->set('pain_level', 9)
            ->set('had_trauma', true)
            ->call('goToStep3')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 3)
            ->assertSet('triage_urgency', 'high');

        $this->assertNotNull($component->get('triage_summary'));
        $this->assertNotNull($component->get('triage_suggested_procedure'));
    }

    public function test_wizard_step_4_loads_slots_and_respects_clinic_schedule_and_blocks(): void
    {
        // Fix a specific date in the future: Monday 2026-10-19
        $futureMonday = CarbonImmutable::parse('2026-10-19 08:00:00');
        $this->travelTo($futureMonday->copy()->subDays(3));

        $clinic = Clinic::factory()->active()->create();

        // Active schedule on Mondays (dayOfWeek = 1) from 08:00 to 11:00, 30 min slots (08:00, 08:30, 09:00, 09:30, 10:00, 10:30)
        ClinicSchedule::factory()->for($clinic)->create([
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '11:00',
            'break_start' => '09:00',
            'break_end' => '10:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Existing appointment at 08:30
        Appointment::factory()->for($clinic)->create([
            'scheduled_at' => $futureMonday->setTime(8, 30),
            'status' => 'confirmed',
        ]);

        Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->set('selected_date', '2026-10-19')
            ->call('goToStep4')
            ->assertSet('currentStep', 4)
            // Available should be 08:00 and 10:00, 10:30 (08:30 is booked, 09:00-10:00 is break)
            ->assertSet('available_slots', ['08:00', '10:00', '10:30']);

        // Test blocked date
        ClinicBlockedDate::factory()->for($clinic)->create([
            'blocked_date' => '2026-10-19',
        ]);

        Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->set('selected_date', '2026-10-19')
            ->call('goToStep4')
            ->assertSet('available_slots', []);
    }

    public function test_wizard_confirms_booking_creates_records_and_generates_whatsapp_url(): void
    {
        $futureDate = CarbonImmutable::parse('2026-11-09 10:00:00');
        $this->travelTo($futureDate->copy()->subDays(5));

        $clinic = Clinic::factory()->active()->create([
            'name' => 'Clínica Dental Sul',
            'whatsapp_number' => '11987654321',
            'ai_provider' => 'none',
        ]);

        ClinicSchedule::factory()->for($clinic)->create([
            'day_of_week' => 1, // Monday
            'start_time' => '08:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        $component = Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->set('patient_name', 'Fernanda Souza')
            ->set('patient_phone', '11912345678')
            ->set('complaint', 'Preciso fazer profilaxia e limpeza de rotina.')
            ->set('pain_level', 1)
            ->call('goToStep3')
            ->assertSet('currentStep', 3)
            ->call('goToStep4')
            ->assertSet('currentStep', 4)
            ->set('selected_date', '2026-11-09')
            ->call('selectTime', '10:00')
            ->call('confirmBooking')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 5);

        $this->assertNotNull($component->get('appointment_id'));

        $this->assertDatabaseHas('appointments', [
            'clinic_id' => $clinic->id,
            'patient_name' => 'Fernanda Souza',
            'patient_phone' => '11912345678',
            'scheduled_at' => '2026-11-09 10:00:00',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('triage_records', [
            'raw_complaint' => 'Preciso fazer profilaxia e limpeza de rotina.',
            'pain_level' => 1,
            'urgency_level' => 'low',
        ]);
    }

    public function test_double_booking_collision_is_prevented_and_displays_error(): void
    {
        $futureDate = CarbonImmutable::parse('2026-11-16 09:00:00');
        $this->travelTo($futureDate->copy()->subDays(5));

        $clinic = Clinic::factory()->active()->create();

        ClinicSchedule::factory()->for($clinic)->create([
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Competitor books the 09:00 slot first
        Appointment::factory()->for($clinic)->create([
            'scheduled_at' => '2026-11-16 09:00:00',
            'status' => 'pending',
        ]);

        Livewire::test(ClinicBookingWizard::class, ['clinic' => $clinic])
            ->set('patient_name', 'Paciente Concorrente')
            ->set('patient_phone', '11999990000')
            ->set('complaint', 'Consulta de rotina')
            ->set('pain_level', 0)
            ->set('currentStep', 4)
            ->set('selected_date', '2026-11-16')
            ->set('selected_time', '09:00')
            ->call('confirmBooking')
            ->assertSet('currentStep', 4)
            ->assertSet('errorMessage', 'O horário selecionado não está mais disponível.');

        // Verify only 1 appointment exists
        $this->assertSame(1, Appointment::withoutGlobalScopes()->where('clinic_id', $clinic->id)->where('scheduled_at', '2026-11-16 09:00:00')->count());
    }
}

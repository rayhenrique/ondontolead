<?php

namespace Tests\Feature\Services;

use App\Exceptions\SlotUnavailableException;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use App\Services\AppointmentBookingService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AppointmentBookingServiceTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_valid_slot_creates_pending_appointment_for_selected_clinic(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:00:00');
        $this->createSchedule($clinic, $scheduledAt);

        $appointment = app(AppointmentBookingService::class)->book(
            $clinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
            'Primeira consulta',
        );

        $this->assertSame($clinic->getKey(), $appointment->clinic_id);
        $this->assertSame('pending', $appointment->status);
        $this->assertSame('Maria da Silva', $appointment->patient_name);
        $this->assertSame('2026-09-21 09:00:00', $appointment->scheduled_at->format('Y-m-d H:i:s'));
        $this->assertModelExists($appointment);
    }

    public function test_slot_outside_schedule_is_rejected_without_creating_appointment(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 18:00:00');
        $this->createSchedule($clinic, $scheduledAt);

        try {
            app(AppointmentBookingService::class)->book(
                $clinic,
                $scheduledAt,
                'Maria da Silva',
                '5585999999999',
            );
            $this->fail('O serviço deveria rejeitar um horário fora da grade.');
        } catch (SlotUnavailableException $exception) {
            $this->assertSame('O horário não pertence à grade de atendimento.', $exception->getMessage());
        }

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_day_without_active_schedule_is_rejected(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:00:00');

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('A clínica não atende no dia selecionado.');

        app(AppointmentBookingService::class)->book(
            $clinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
        );
    }

    public function test_slot_not_aligned_with_grid_is_rejected(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:15:00');
        $this->createSchedule($clinic, $scheduledAt);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('O horário não pertence à grade de atendimento.');

        app(AppointmentBookingService::class)->book(
            $clinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
        );
    }

    public function test_invalid_slot_duration_is_rejected(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:00:00');
        $this->createSchedule($clinic, $scheduledAt)->update(['slot_duration_minutes' => 0]);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('A grade da clínica possui duração de slot inválida.');

        app(AppointmentBookingService::class)->book(
            $clinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
        );
    }

    public function test_slot_overlapping_break_is_rejected(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 12:00:00');
        $this->createSchedule($clinic, $scheduledAt, withBreak: true);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('O horário selecionado coincide com o intervalo da clínica.');

        app(AppointmentBookingService::class)->book(
            $clinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
        );
    }

    public function test_blocked_date_is_rejected(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:00:00');
        $this->createSchedule($clinic, $scheduledAt);
        ClinicBlockedDate::factory()->for($clinic)->create([
            'blocked_date' => $scheduledAt->toDateString(),
        ]);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('A clínica bloqueou a data selecionada.');

        app(AppointmentBookingService::class)->book(
            $clinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
        );
    }

    public function test_existing_appointment_prevents_duplicate_slot(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:00:00');
        $this->createSchedule($clinic, $scheduledAt);
        Appointment::factory()->for($clinic)->create(['scheduled_at' => $scheduledAt]);

        try {
            app(AppointmentBookingService::class)->book(
                $clinic,
                $scheduledAt,
                'Outro paciente',
                '5585888888888',
            );
            $this->fail('O serviço deveria rejeitar um horário ocupado.');
        } catch (SlotUnavailableException $exception) {
            $this->assertSame('O horário selecionado não está mais disponível.', $exception->getMessage());
        }

        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_same_time_can_be_booked_by_different_clinics(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        $firstClinic = Clinic::factory()->create();
        $secondClinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:00:00');
        $this->createSchedule($firstClinic, $scheduledAt);
        $this->createSchedule($secondClinic, $scheduledAt);
        Appointment::factory()->for($firstClinic)->create(['scheduled_at' => $scheduledAt]);

        $appointment = app(AppointmentBookingService::class)->book(
            $secondClinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
        );

        $this->assertSame($secondClinic->getKey(), $appointment->clinic_id);
        $this->assertDatabaseCount('appointments', 2);
    }

    public function test_past_slot_is_rejected(): void
    {
        $this->travelTo('2026-09-21 10:00:00');
        $clinic = Clinic::factory()->create();
        $scheduledAt = CarbonImmutable::parse('2026-09-21 09:00:00');
        $this->createSchedule($clinic, $scheduledAt);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('Selecione um horário futuro.');

        app(AppointmentBookingService::class)->book(
            $clinic,
            $scheduledAt,
            'Maria da Silva',
            '5585999999999',
        );
    }

    private function createSchedule(
        Clinic $clinic,
        CarbonImmutable $scheduledAt,
        bool $withBreak = false,
    ): ClinicSchedule {
        $factory = ClinicSchedule::factory()->for($clinic);

        if ($withBreak) {
            $factory = $factory->withBreak();
        }

        return $factory->create([
            'day_of_week' => $scheduledAt->dayOfWeek,
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
            'slot_duration_minutes' => 30,
        ]);
    }
}

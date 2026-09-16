<?php

namespace App\Services;

use App\Exceptions\SlotUnavailableException;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class AppointmentBookingService
{
    public function book(
        Clinic $clinic,
        CarbonInterface $scheduledAt,
        string $patientName,
        string $patientPhone,
        ?string $notes = null,
    ): Appointment {
        $slotStartsAt = CarbonImmutable::instance($scheduledAt)
            ->setTimezone(config('app.timezone'));

        try {
            return DB::transaction(function () use (
                $clinic,
                $slotStartsAt,
                $patientName,
                $patientPhone,
                $notes,
            ): Appointment {
                Clinic::query()
                    ->whereKey($clinic->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $schedule = ClinicSchedule::withoutGlobalScopes()
                    ->where('clinic_id', $clinic->getKey())
                    ->where('day_of_week', $slotStartsAt->dayOfWeek)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if ($schedule === null) {
                    throw new SlotUnavailableException('A clínica não atende no dia selecionado.');
                }

                $this->ensureFutureSlot($slotStartsAt);
                $this->ensureSlotFitsSchedule($schedule, $slotStartsAt);
                $this->ensureDateIsNotBlocked($clinic, $slotStartsAt);
                $this->ensureSlotIsFree($clinic, $slotStartsAt);

                return Appointment::withoutGlobalScopes()->create([
                    'clinic_id' => $clinic->getKey(),
                    'patient_name' => $patientName,
                    'patient_phone' => $patientPhone,
                    'scheduled_at' => $slotStartsAt,
                    'status' => 'pending',
                    'notes' => $notes,
                ]);
            }, 3);
        } catch (UniqueConstraintViolationException $exception) {
            throw new SlotUnavailableException(
                'O horário selecionado acabou de ser reservado.',
                previous: $exception,
            );
        }
    }

    private function ensureFutureSlot(CarbonImmutable $slotStartsAt): void
    {
        if ($slotStartsAt->lessThanOrEqualTo(now())) {
            throw new SlotUnavailableException('Selecione um horário futuro.');
        }
    }

    private function ensureSlotFitsSchedule(
        ClinicSchedule $schedule,
        CarbonImmutable $slotStartsAt,
    ): void {
        if ($schedule->slot_duration_minutes <= 0) {
            throw new SlotUnavailableException('A grade da clínica possui duração de slot inválida.');
        }

        $scheduleStartsAt = $this->atScheduleTime($slotStartsAt, $schedule->start_time);
        $scheduleEndsAt = $this->atScheduleTime($slotStartsAt, $schedule->end_time);
        $slotEndsAt = $slotStartsAt->addMinutes($schedule->slot_duration_minutes);

        $isAligned = ($slotStartsAt->getTimestamp() - $scheduleStartsAt->getTimestamp())
            % ($schedule->slot_duration_minutes * 60) === 0;

        if ($slotStartsAt->lessThan($scheduleStartsAt)
            || $slotEndsAt->greaterThan($scheduleEndsAt)
            || ! $isAligned) {
            throw new SlotUnavailableException('O horário não pertence à grade de atendimento.');
        }

        if ($schedule->break_start === null || $schedule->break_end === null) {
            return;
        }

        $breakStartsAt = $this->atScheduleTime($slotStartsAt, $schedule->break_start);
        $breakEndsAt = $this->atScheduleTime($slotStartsAt, $schedule->break_end);

        if ($slotStartsAt->lessThan($breakEndsAt) && $slotEndsAt->greaterThan($breakStartsAt)) {
            throw new SlotUnavailableException('O horário selecionado coincide com o intervalo da clínica.');
        }
    }

    private function ensureDateIsNotBlocked(Clinic $clinic, CarbonImmutable $slotStartsAt): void
    {
        $blockedDate = ClinicBlockedDate::withoutGlobalScopes()
            ->where('clinic_id', $clinic->getKey())
            ->whereDate('blocked_date', $slotStartsAt->toDateString())
            ->lockForUpdate()
            ->first();

        if ($blockedDate !== null) {
            throw new SlotUnavailableException('A clínica bloqueou a data selecionada.');
        }
    }

    private function ensureSlotIsFree(Clinic $clinic, CarbonImmutable $slotStartsAt): void
    {
        $appointment = Appointment::withoutGlobalScopes()
            ->where('clinic_id', $clinic->getKey())
            ->where('scheduled_at', $slotStartsAt)
            ->lockForUpdate()
            ->first();

        if ($appointment !== null) {
            throw new SlotUnavailableException('O horário selecionado não está mais disponível.');
        }
    }

    private function atScheduleTime(CarbonImmutable $date, string $time): CarbonImmutable
    {
        return CarbonImmutable::parse(
            $date->toDateString().' '.$time,
            $date->getTimezone(),
        );
    }
}

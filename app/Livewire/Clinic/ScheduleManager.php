<?php

namespace App\Livewire\Clinic;

use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ScheduleManager extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $schedules = [];

    /** @var array<int, array<string, mixed>> */
    public array $blockedDates = [];

    public string $newBlockedDate = '';

    public string $newBlockedReason = '';

    public ?string $feedbackMessage = null;

    public function mount(): void
    {
        $this->loadSchedules();
        $this->loadBlockedDates();
    }

    public function loadSchedules(): void
    {
        $clinic = $this->getClinic();

        if ($clinic === null) {
            return;
        }

        $existing = ClinicSchedule::query()
            ->where('clinic_id', $clinic->id)
            ->get()
            ->keyBy('day_of_week');

        $this->schedules = [];

        for ($day = 0; $day <= 6; $day++) {
            $item = $existing->get($day);

            $this->schedules[$day] = [
                'day_of_week' => $day,
                'is_active' => $item ? (bool) $item->is_active : in_array($day, [1, 2, 3, 4, 5]),
                'start_time' => $item && $item->start_time ? substr($item->start_time, 0, 5) : '08:00',
                'end_time' => $item && $item->end_time ? substr($item->end_time, 0, 5) : '18:00',
                'break_start' => $item && $item->break_start ? substr($item->break_start, 0, 5) : '12:00',
                'break_end' => $item && $item->break_end ? substr($item->break_end, 0, 5) : '13:00',
                'slot_duration_minutes' => $item ? (int) $item->slot_duration_minutes : 30,
            ];
        }
    }

    public function loadBlockedDates(): void
    {
        $clinic = $this->getClinic();

        if ($clinic === null) {
            return;
        }

        $this->blockedDates = ClinicBlockedDate::query()
            ->where('clinic_id', $clinic->id)
            ->whereDate('blocked_date', '>=', Carbon::today())
            ->orderBy('blocked_date')
            ->get()
            ->map(fn (ClinicBlockedDate $date): array => [
                'id' => $date->id,
                'blocked_date' => $date->blocked_date->format('Y-m-d'),
                'formatted_date' => $date->blocked_date->format('d/m/Y'),
                'reason' => $date->reason,
            ])
            ->all();
    }

    public function saveSchedules(): void
    {
        $clinic = $this->getClinic();
        abort_unless($clinic !== null, 403);

        $this->validate([
            'schedules.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'schedules.*.is_active' => ['boolean'],
            'schedules.*.start_time' => ['required', 'date_format:H:i'],
            'schedules.*.end_time' => ['required', 'date_format:H:i', 'after:schedules.*.start_time'],
            'schedules.*.break_start' => ['nullable', 'date_format:H:i'],
            'schedules.*.break_end' => ['nullable', 'date_format:H:i'],
            'schedules.*.slot_duration_minutes' => ['required', 'integer', 'min:5', 'max:240'],
        ]);

        foreach ($this->schedules as $day => $data) {
            ClinicSchedule::query()->updateOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'day_of_week' => $day,
                ],
                [
                    'is_active' => (bool) $data['is_active'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'break_start' => ! empty($data['break_start']) ? $data['break_start'] : null,
                    'break_end' => ! empty($data['break_end']) ? $data['break_end'] : null,
                    'slot_duration_minutes' => (int) $data['slot_duration_minutes'],
                ]
            );
        }

        $this->feedbackMessage = 'Grade de horários da semana atualizada com sucesso!';
        $this->loadSchedules();
    }

    public function addBlockedDate(): void
    {
        $clinic = $this->getClinic();
        abort_unless($clinic !== null, 403);

        $this->validate([
            'newBlockedDate' => ['required', 'date', 'after_or_equal:today'],
            'newBlockedReason' => ['nullable', 'string', 'max:150'],
        ]);

        ClinicBlockedDate::query()->updateOrCreate(
            [
                'clinic_id' => $clinic->id,
                'blocked_date' => $this->newBlockedDate,
            ],
            [
                'reason' => $this->newBlockedReason ?: null,
            ]
        );

        $this->newBlockedDate = '';
        $this->newBlockedReason = '';
        $this->feedbackMessage = 'Data bloqueada adicionada com sucesso!';
        $this->loadBlockedDates();
    }

    public function removeBlockedDate(int $blockedDateId): void
    {
        $clinic = $this->getClinic();
        abort_unless($clinic !== null, 403);

        ClinicBlockedDate::query()
            ->where('clinic_id', $clinic->id)
            ->where('id', $blockedDateId)
            ->delete();

        $this->feedbackMessage = 'Bloqueio de data removido com sucesso!';
        $this->loadBlockedDates();
    }

    public function render(): View
    {
        $dayNames = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
        ];

        return view('livewire.clinic.schedule-manager', compact('dayNames'));
    }

    private function getClinic(): ?Clinic
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user?->clinic ?? ($user?->is_superadmin ? Clinic::query()->first() : null);
    }
}

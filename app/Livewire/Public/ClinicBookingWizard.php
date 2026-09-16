<?php

namespace App\Livewire\Public;

use App\Exceptions\SlotUnavailableException;
use App\Models\Clinic;
use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Data\TriageResult;
use App\Services\AiTriageService;
use App\Services\AppointmentBookingService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ClinicBookingWizard extends Component
{
    public Clinic $clinic;

    public int $currentStep = 1;

    // Step 1: Informações do Paciente
    public string $patient_name = '';

    public string $patient_phone = '';

    // Step 2: Anamnese e Sintomas
    public string $complaint = '';

    public int $pain_level = 0;

    public bool $has_swelling = false;

    public bool $has_bleeding = false;

    public bool $had_trauma = false;

    public bool $has_fever = false;

    public bool $has_breathing_difficulty = false;

    public ?string $medical_history = null;

    // Step 3: Retorno da Triagem
    public ?string $triage_urgency = null;

    public ?string $triage_summary = null;

    public ?string $triage_suggested_procedure = null;

    public bool $triage_processed_by_ai = false;

    // Step 4: Escolha de Data e Horário
    public string $selected_date = '';

    public string $selected_time = '';

    /** @var list<string> */
    public array $available_slots = [];

    public ?string $errorMessage = null;

    // Step 5: Confirmação & Transbordo WhatsApp
    public ?int $appointment_id = null;

    public ?string $whatsapp_url = null;

    public ?string $confirmed_datetime_formatted = null;

    public function mount(Clinic $clinic): void
    {
        $this->clinic = $clinic;
        $this->selected_date = Carbon::today()->format('Y-m-d');
    }

    public function goToStep2(): void
    {
        $this->validate([
            'patient_name' => ['required', 'string', 'min:3', 'max:150'],
            'patient_phone' => ['required', 'string', 'min:10', 'max:25'],
        ]);

        $this->currentStep = 2;
        $this->errorMessage = null;
    }

    public function goToStep3(AiTriageService $aiTriageService): void
    {
        $this->validate([
            'complaint' => ['required', 'string', 'min:5', 'max:1000'],
            'pain_level' => ['required', 'integer', 'min:0', 'max:10'],
            'has_swelling' => ['boolean'],
            'has_bleeding' => ['boolean'],
            'had_trauma' => ['boolean'],
            'has_fever' => ['boolean'],
            'has_breathing_difficulty' => ['boolean'],
            'medical_history' => ['nullable', 'string', 'max:500'],
        ]);

        $input = $this->buildTriageInput();
        $result = $aiTriageService->analyze($this->clinic, $input);

        $this->triage_urgency = $result->urgencyLevel;
        $this->triage_summary = $result->summary;
        $this->triage_suggested_procedure = $result->suggestedProcedure;
        $this->triage_processed_by_ai = $result->processedByAi;

        $this->currentStep = 3;
        $this->errorMessage = null;
    }

    public function goToStep4(AppointmentBookingService $bookingService): void
    {
        $this->loadSlots($bookingService);
        $this->currentStep = 4;
        $this->errorMessage = null;
    }

    public function updatedSelectedDate(): void
    {
        $this->selected_time = '';
        $this->errorMessage = null;
        $this->loadSlots(app(AppointmentBookingService::class));
    }

    public function selectTime(string $time): void
    {
        $this->selected_time = $time;
        $this->errorMessage = null;
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1 && $this->currentStep < 5) {
            $this->currentStep--;
            $this->errorMessage = null;
        }
    }

    public function confirmBooking(AppointmentBookingService $bookingService): void
    {
        $this->validate([
            'selected_date' => ['required', 'date', 'after_or_equal:today'],
            'selected_time' => ['required', 'date_format:H:i'],
        ]);

        $this->errorMessage = null;

        try {
            $scheduledAt = CarbonImmutable::parse(
                $this->selected_date.' '.$this->selected_time,
                config('app.timezone')
            );

            $triageInput = $this->buildTriageInput();
            $triageResult = new TriageResult(
                summary: $this->triage_summary ?? 'Triagem realizada no agendamento.',
                urgencyLevel: $this->triage_urgency ?? 'low',
                suggestedProcedure: $this->triage_suggested_procedure ?? 'Consulta Inicial',
                processedByAi: $this->triage_processed_by_ai,
            );

            $appointment = $bookingService->book(
                clinic: $this->clinic,
                scheduledAt: $scheduledAt,
                patientName: $this->patient_name,
                patientPhone: $this->patient_phone,
                notes: 'Agendado pelo formulário online da clínica.',
                triageResult: $triageResult,
                triageInput: $triageInput,
            );

            $this->appointment_id = $appointment->id;
            $this->confirmed_datetime_formatted = $scheduledAt->format('d/m/Y \à\s H:i');

            // Gerar link do WhatsApp com mensagem formatada
            $this->whatsapp_url = $this->generateWhatsAppUrl($scheduledAt);
            $this->currentStep = 5;
        } catch (SlotUnavailableException $e) {
            $this->errorMessage = $e->getMessage();
            $this->loadSlots($bookingService);
        }
    }

    private function loadSlots(AppointmentBookingService $bookingService): void
    {
        if (blank($this->selected_date)) {
            $this->available_slots = [];

            return;
        }

        $date = Carbon::parse($this->selected_date, config('app.timezone'));
        $this->available_slots = $bookingService->getAvailableSlots($this->clinic, $date);
    }

    private function buildTriageInput(): TriageInput
    {
        return new TriageInput(
            complaint: $this->complaint,
            painLevel: $this->pain_level,
            hasSwelling: $this->has_swelling,
            hasBleeding: $this->has_bleeding,
            hadTrauma: $this->had_trauma,
            hasFever: $this->has_fever,
            hasBreathingDifficulty: $this->has_breathing_difficulty,
            medicalHistory: $this->medical_history,
        );
    }

    private function generateWhatsAppUrl(CarbonImmutable $scheduledAt): string
    {
        $rawClinicPhone = preg_replace('/\D/', '', $this->clinic->whatsapp_number ?? '');

        // Adiciona DDI 55 se ausente
        $cleanPhone = str_starts_with($rawClinicPhone, '55') ? $rawClinicPhone : '55'.$rawClinicPhone;

        $urgencyLabel = match ($this->triage_urgency) {
            'urgent' => 'Urgente',
            'high' => 'Alta',
            'medium' => 'Média',
            default => 'Baixa',
        };

        $lines = [
            "👋 Olá! Acabei de realizar meu pré-agendamento pelo site da *{$this->clinic->name}*!",
            '',
            "👤 *Paciente:* {$this->patient_name}",
            "📅 *Data:* {$scheduledAt->format('d/m/Y \à\s H:i')} hrs",
            "⚠️ *Urgência Avaliada:* {$urgencyLabel}",
            "🩺 *Procedimento Indicado:* {$this->triage_suggested_procedure}",
            "📝 *Queixa Principal:* \"{$this->complaint}\"",
        ];

        if (! empty($this->medical_history)) {
            $lines[] = "💊 *Histórico/Alergias:* {$this->medical_history}";
        }

        $lines[] = '';
        $lines[] = 'Gostaria de confirmar meu horário de atendimento. Aguardo retorno!';

        $message = implode("\n", $lines);

        return "https://wa.me/{$cleanPhone}?text=".urlencode($message);
    }

    public function render(): View
    {
        return view('livewire.public.clinic-booking-wizard');
    }
}

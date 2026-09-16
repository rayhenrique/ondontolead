<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicBlockedDate;
use App\Models\ClinicSchedule;
use App\Models\Plan;
use App\Models\TriageRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DemoClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily authenticate as superadmin to bypass TenantScope in CLI
        $superAdmin = User::query()->where('is_superadmin', true)->first();
        if ($superAdmin) {
            Auth::login($superAdmin);
        }

        // 1. Obter Plano Profissional ou o primeiro plano ativo
        $plan = Plan::query()->where('slug', 'profissional')->first()
            ?? Plan::query()->first();

        // 2. Criar ou Atualizar a Clínica Demo
        $clinic = Clinic::query()->updateOrCreate(
            ['slug' => 'odontovida'],
            [
                'name' => 'OdontoVida Odontologia Integrada',
                'plan_id' => $plan?->id,
                'whatsapp_number' => '(11) 98765-4321',
                'ai_provider' => 'gemini',
                'subscription_status' => 'trial',
                'trial_ends_at' => Carbon::now()->addDays(12),
            ]
        );

        // 3. Criar Usuária Dentista / Administradora da Clínica
        $user = User::query()->updateOrCreate(
            ['email' => 'dra.camila@odontovida.com.br'],
            [
                'name' => 'Dra. Camila Rodrigues',
                'password' => Hash::make('password'),
                'clinic_id' => $clinic->id,
                'email_verified_at' => Carbon::now(),
            ]
        );
        $user->forceFill(['is_superadmin' => false])->save();

        // 4. Grade Semanal de Atendimento (Segunda a Sábado)
        $scheduleDays = [
            1 => ['active' => true, 'start' => '08:00:00', 'end' => '18:00:00', 'b_start' => '12:00:00', 'b_end' => '13:00:00', 'slot' => 30], // Seg
            2 => ['active' => true, 'start' => '08:00:00', 'end' => '18:00:00', 'b_start' => '12:00:00', 'b_end' => '13:00:00', 'slot' => 30], // Ter
            3 => ['active' => true, 'start' => '08:00:00', 'end' => '18:00:00', 'b_start' => '12:00:00', 'b_end' => '13:00:00', 'slot' => 30], // Qua
            4 => ['active' => true, 'start' => '08:00:00', 'end' => '18:00:00', 'b_start' => '12:00:00', 'b_end' => '13:00:00', 'slot' => 30], // Qui
            5 => ['active' => true, 'start' => '08:00:00', 'end' => '18:00:00', 'b_start' => '12:00:00', 'b_end' => '13:00:00', 'slot' => 30], // Sex
            6 => ['active' => true, 'start' => '08:00:00', 'end' => '12:30:00', 'b_start' => null, 'b_end' => null, 'slot' => 30],             // Sáb
            0 => ['active' => false, 'start' => '08:00:00', 'end' => '18:00:00', 'b_start' => null, 'b_end' => null, 'slot' => 30],            // Dom
        ];

        foreach ($scheduleDays as $dayOfWeek => $config) {
            ClinicSchedule::withoutGlobalScopes()->updateOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'is_active' => $config['active'],
                    'start_time' => $config['start'],
                    'end_time' => $config['end'],
                    'break_start' => $config['b_start'],
                    'break_end' => $config['b_end'],
                    'slot_duration_minutes' => $config['slot'],
                ]
            );
        }

        // 5. Datas Bloqueadas (Feriado & Congresso)
        $holidayDate = Carbon::now()->addDays(14)->format('Y-m-d');
        if (! ClinicBlockedDate::withoutGlobalScopes()->where('clinic_id', $clinic->id)->whereDate('blocked_date', $holidayDate)->exists()) {
            ClinicBlockedDate::withoutGlobalScopes()->create([
                'clinic_id' => $clinic->id,
                'blocked_date' => $holidayDate,
                'reason' => 'Feriado Municipal de São Paulo',
            ]);
        }

        $congressDate = Carbon::now()->addDays(28)->format('Y-m-d');
        if (! ClinicBlockedDate::withoutGlobalScopes()->where('clinic_id', $clinic->id)->whereDate('blocked_date', $congressDate)->exists()) {
            ClinicBlockedDate::withoutGlobalScopes()->create([
                'clinic_id' => $clinic->id,
                'blocked_date' => $congressDate,
                'reason' => 'Participação no CIOSP (Congresso Internacional de Odontologia)',
            ]);
        }

        // 6. Lista de Agendamentos & Triagens Realistas
        $appointmentsData = [
            // --- HOJE ---
            [
                'scheduled_at' => Carbon::today()->setHour(9)->setMinute(30),
                'patient_name' => 'Lucas Silveira',
                'patient_phone' => '(11) 99123-4567',
                'status' => 'confirmed',
                'notes' => 'Paciente relatou dor forte desde ontem.',
                'triage' => [
                    'raw_complaint' => 'Dor de dente aguda no molar inferior direito, não consigo mastigar nem beber nada gelado.',
                    'pain_level' => 8,
                    'urgency_level' => 'high',
                    'suggested_procedure' => 'Urgência Odontológica / Endodontia',
                    'ai_summary' => 'Paciente apresenta dor pulsátil intensa e espontânea no elemento 46, com exacerbação a estímulos térmicos. Forte indicativo de pulpite irreversível sintomática.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::today()->setHour(14)->setMinute(0),
                'patient_name' => 'Mariana Duarte',
                'patient_phone' => '(11) 98234-5678',
                'status' => 'confirmed',
                'notes' => 'Interesse em clareamento para casamento em 2 meses.',
                'triage' => [
                    'raw_complaint' => 'Gostaria de fazer uma limpeza de rotina e tirar dúvidas sobre clareamento a laser para o meu casamento.',
                    'pain_level' => 0,
                    'urgency_level' => 'low',
                    'suggested_procedure' => 'Profilaxia e Clareamento Dental',
                    'ai_summary' => 'Consulta preventiva e estética para remoção de biofilme dental e planejamento de clareamento estético.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::today()->setHour(16)->setMinute(30),
                'patient_name' => 'Carlos Eduardo Meireles',
                'patient_phone' => '(11) 97345-6789',
                'status' => 'pending',
                'notes' => 'Aguardando confirmação via WhatsApp.',
                'triage' => [
                    'raw_complaint' => 'Quebrei um pedaço da restauração de trás comendo amendoim ontem à noite. Está arranhando a língua.',
                    'pain_level' => 3,
                    'urgency_level' => 'medium',
                    'suggested_procedure' => 'Restauração Estética em Resina',
                    'ai_summary' => 'Fratura parcial de restauração em resina composta no pré-molar superior. Sem dor aguda ou envolvimento pulpar aparente.',
                    'processed_by_ai' => true,
                ],
            ],

            // --- AMANHÃ ---
            [
                'scheduled_at' => Carbon::tomorrow()->setHour(10)->setMinute(0),
                'patient_name' => 'Beatriz Nogueira',
                'patient_phone' => '(11) 96456-7890',
                'status' => 'confirmed',
                'notes' => 'Primeira vez na clínica.',
                'triage' => [
                    'raw_complaint' => 'Gengiva sangrando muito ao escovar os dentes e sinto um inchaço perto do canino.',
                    'pain_level' => 4,
                    'urgency_level' => 'medium',
                    'suggested_procedure' => 'Terapia Periodontal e Raspagem',
                    'ai_summary' => 'Quadro de gengivite com sangramento marginal ativo e inflamação tecidual localizada no setor anterior.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::tomorrow()->setHour(15)->setMinute(30),
                'patient_name' => 'Rafael Alcantara',
                'patient_phone' => '(11) 95567-8901',
                'status' => 'confirmed',
                'notes' => 'Traz tomografia recente.',
                'triage' => [
                    'raw_complaint' => 'Perdi um dente há alguns anos e agora quero colocar um implante dentário fixo.',
                    'pain_level' => 0,
                    'urgency_level' => 'low',
                    'suggested_procedure' => 'Avaliação Cirúrgica para Implante',
                    'ai_summary' => 'Edentulismo parcial com indicação para reabilitação com implante osseointegrável e tomografia prévia.',
                    'processed_by_ai' => true,
                ],
            ],

            // --- ESTA SEMANA (Próximos dias) ---
            [
                'scheduled_at' => Carbon::now()->addDays(2)->setHour(11)->setMinute(0),
                'patient_name' => 'Fernanda Costa',
                'patient_phone' => '(11) 94678-9012',
                'status' => 'confirmed',
                'notes' => 'Encaixe de urgência.',
                'triage' => [
                    'raw_complaint' => 'Inchaço visível na bochecha esquerda, com dor constante e febre baixa desde ontem.',
                    'pain_level' => 9,
                    'urgency_level' => 'high',
                    'suggested_procedure' => 'Drenagem de Abscesso e Urgência',
                    'ai_summary' => 'Quadro infeccioso agudo com edema facial unilateral e febre. Necessita de intervenção clínica imediata para drenagem.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::now()->addDays(3)->setHour(9)->setMinute(0),
                'patient_name' => 'Thiago Prado',
                'patient_phone' => '(11) 93789-0123',
                'status' => 'pending',
                'notes' => 'Sensibilidade ao frio.',
                'triage' => [
                    'raw_complaint' => 'Sensibilidade nos dentes da frente ao respirar ar gelado ou tomar água fria.',
                    'pain_level' => 2,
                    'urgency_level' => 'low',
                    'suggested_procedure' => 'Dessensibilização Dentinária',
                    'ai_summary' => 'Hipersensibilidade dentinária cervical em incisivos superiores, possivelmente ligada a recessão gengival leve.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::now()->addDays(4)->setHour(14)->setMinute(30),
                'patient_name' => 'Juliana Mendes',
                'patient_phone' => '(11) 92890-1234',
                'status' => 'confirmed',
                'notes' => 'Guardou o laminado em soro.',
                'triage' => [
                    'raw_complaint' => 'Uma faceta de porcelana descolou enquanto almoçava hoje.',
                    'pain_level' => 1,
                    'urgency_level' => 'medium',
                    'suggested_procedure' => 'Recimentação de Faceta Estética',
                    'ai_summary' => 'Descolamento de laminado cerâmico estético sem lesão estrutural aparente no preparo biológico.',
                    'processed_by_ai' => true,
                ],
            ],

            // --- PASSADOS (Concluídos / Histórico) ---
            [
                'scheduled_at' => Carbon::yesterday()->setHour(10)->setMinute(30),
                'patient_name' => 'Ana Clara Fagundes',
                'patient_phone' => '(11) 91901-2345',
                'status' => 'completed',
                'notes' => 'Procedimento realizado com sucesso. Prescrito analgésico.',
                'triage' => [
                    'raw_complaint' => 'Dente do siso inflamado empurrando os outros dentes.',
                    'pain_level' => 6,
                    'urgency_level' => 'medium',
                    'suggested_procedure' => 'Extração de Terceiro Molar (Siso)',
                    'ai_summary' => 'Pericoronarite associada ao terceiro molar inferior semi-incluso.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::now()->subDays(2)->setHour(15)->setMinute(0),
                'patient_name' => 'Bruno Henrique Dias',
                'patient_phone' => '(11) 91012-3456',
                'status' => 'completed',
                'notes' => 'Retorno agendado para 6 meses.',
                'triage' => [
                    'raw_complaint' => 'Revisão semestral preventiva e profilaxia com ultrassom.',
                    'pain_level' => 0,
                    'urgency_level' => 'low',
                    'suggested_procedure' => 'Check-up Preventivo e Profilaxia',
                    'ai_summary' => 'Manutenção periódica de saúde bucal e profilaxia com jato de bicarbonato.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::now()->subDays(3)->setHour(16)->setMinute(0),
                'patient_name' => 'Gabriel Vasconcelos',
                'patient_phone' => '(11) 90123-4567',
                'status' => 'no_show',
                'notes' => 'Não atendeu às tentativas de contato.',
                'triage' => [
                    'raw_complaint' => 'Avaliação ortodôntica para alinhadores invisíveis.',
                    'pain_level' => 0,
                    'urgency_level' => 'low',
                    'suggested_procedure' => 'Consulta Inicial de Ortodontia',
                    'ai_summary' => 'Avaliação ortodôntica e planejamento digital com escaneamento intraoral.',
                    'processed_by_ai' => true,
                ],
            ],
            [
                'scheduled_at' => Carbon::now()->subDays(4)->setHour(11)->setMinute(30),
                'patient_name' => 'Aline Ferreira',
                'patient_phone' => '(11) 99234-5678',
                'status' => 'canceled',
                'notes' => 'Cancelou por motivo de viagem a trabalho.',
                'triage' => [
                    'raw_complaint' => 'Troca de restauração escura amálgama antiga por resina branca.',
                    'pain_level' => 1,
                    'urgency_level' => 'low',
                    'suggested_procedure' => 'Substituição de Amálgama por Resina',
                    'ai_summary' => 'Substituição eletiva de restaurações metálicas antigas por resina nanoparticulada estética.',
                    'processed_by_ai' => true,
                ],
            ],
        ];

        foreach ($appointmentsData as $data) {
            $triageData = $data['triage'];
            unset($data['triage']);

            $appointment = Appointment::withoutGlobalScopes()->firstOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'patient_phone' => $data['patient_phone'],
                    'scheduled_at' => $data['scheduled_at'],
                ],
                [
                    'patient_name' => $data['patient_name'],
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                ]
            );

            TriageRecord::query()->firstOrCreate(
                ['appointment_id' => $appointment->id],
                $triageData
            );
        }

        if ($superAdmin) {
            Auth::logout();
        }
    }
}

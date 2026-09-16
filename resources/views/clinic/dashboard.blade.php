<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2.5 flex-wrap gap-y-1">
                    <h2 class="font-heading font-black text-2xl text-slate-900 tracking-tight leading-tight">
                        {{ $clinic->name }}
                    </h2>
                    @if ($clinic->subscription_status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200/60">
                            Assinatura Ativa
                        </span>
                    @elseif ($clinic->subscription_status === 'trial')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-100 text-sky-800 border border-sky-200/60">
                            Período de Testes (Trial)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200/60">
                            Pendente
                        </span>
                    @endif
                </div>
                <div class="flex items-center space-x-3 text-xs text-slate-500 mt-1.5 flex-wrap">
                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $clinic->whatsapp_number ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                        WhatsApp: <strong class="text-slate-700">{{ $clinic->whatsapp_number ?? 'Não configurado' }}</strong>
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        IA BYOK: <strong class="uppercase text-sky-700 font-bold">{{ $clinic->ai_provider !== 'none' ? $clinic->ai_provider : 'Fallback Determinístico' }}</strong>
                    </span>
                </div>
            </div>

            <!-- Public Link Box with Copy Button -->
            <div x-data="{ copied: false }" class="bg-gradient-to-r from-sky-50/90 to-emerald-50/80 border border-sky-200/80 rounded-2xl px-4 py-2.5 flex items-center justify-between gap-3 text-xs shadow-xs">
                <div class="min-w-0">
                    <span class="text-slate-500 font-semibold text-[11px] block">Página Pública de Agendamento:</span>
                    <a href="{{ url('/' . $clinic->slug) }}" target="_blank" class="font-mono text-sky-800 hover:text-sky-950 font-bold truncate block max-w-xs sm:max-w-sm">
                        {{ url('/' . $clinic->slug) }}
                    </a>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" 
                            @click="navigator.clipboard.writeText('{{ url('/' . $clinic->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                            class="p-2 bg-white text-slate-600 hover:text-sky-600 rounded-xl border border-slate-200 shadow-2xs transition" 
                            :title="copied ? 'Copiado!' : 'Copiar link'">
                        <template x-if="!copied">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        </template>
                        <template x-if="copied">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </template>
                    </button>
                    <a href="{{ url('/' . $clinic->slug) }}" target="_blank" class="p-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl shadow-2xs transition" title="Abrir página pública">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-2xl shadow-xs flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Trial Banner (14 dias grátis sem cartão de crédito) -->
            @if ($clinic->subscription_status === 'trial')
                @php
                    $daysRemaining = $clinic->trial_ends_at ? max(0, (int) now()->diffInDays($clinic->trial_ends_at, false)) : 14;
                @endphp
                <div class="p-6 bg-gradient-to-r from-sky-500/10 via-indigo-500/5 to-emerald-500/10 border border-sky-200/80 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-5 backdrop-blur-xs">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-tr from-sky-600 to-indigo-600 text-white rounded-2xl flex flex-col items-center justify-center font-heading font-black text-sm shrink-0 shadow-md shadow-sky-600/20">
                            <span>{{ $daysRemaining }}</span>
                            <span class="text-[9px] uppercase tracking-wider font-extrabold leading-none">dias</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-heading font-extrabold text-base text-slate-900">Período de Testes Gratuito (Trial)</h4>
                                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase rounded-full border border-emerald-200">
                                    Sem Cartão de Crédito
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                Sua clínica pode usar todos os recursos de triagem e agendamento inteligente gratuitamente por 14 dias.
                                @if ($clinic->trial_ends_at)
                                    Válido até <strong>{{ $clinic->trial_ends_at->format('d/m/Y') }}</strong> (restam {{ $daysRemaining }} dia{{ $daysRemaining === 1 ? '' : 's' }}).
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('app.schedule') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs rounded-xl border border-slate-200 shadow-xs transition">
                            <span>Configurar Grade Semanal</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Onboarding Alert if schedule is missing -->
            @if (! $hasSchedule)
                <div class="p-5 bg-amber-50/90 border border-amber-200/90 rounded-2xl shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-heading font-extrabold text-sm text-amber-950">Grade de atendimento não configurada</h4>
                            <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">Sua clínica ainda não ativou horários de atendimento na grade semanal. Os pacientes não conseguirão agendar horários até que você configure seus dias disponíveis.</p>
                        </div>
                    </div>
                    <a href="{{ route('app.schedule') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl uppercase tracking-wider shrink-0 transition shadow-sm shadow-amber-600/20">
                        <span>Configurar Grade</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            @endif

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Hoje -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-slate-300 transition">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider font-mono">Agendados Hoje</p>
                        <h3 class="text-3xl font-heading font-black text-slate-900 mt-1">{{ $todayCount }}</h3>
                        <p class="text-xs text-sky-600 mt-1 font-semibold">Pacientes aguardados</p>
                    </div>
                    <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center border border-sky-100 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>

                <!-- Próximos 7 dias -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-slate-300 transition">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider font-mono">Próximos 7 Dias</p>
                        <h3 class="text-3xl font-heading font-black text-slate-900 mt-1">{{ $upcomingWeekCount }}</h3>
                        <p class="text-xs text-emerald-600 mt-1 font-semibold">Fluxo confirmado</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>

                <!-- Mês atual -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-slate-300 transition">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider font-mono">Total do Mês</p>
                        <h3 class="text-3xl font-heading font-black text-slate-900 mt-1">{{ $monthCount }}</h3>
                        <p class="text-xs text-indigo-600 mt-1 font-semibold">Agendamentos captados</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>

                <!-- Status rápidos -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-slate-300 transition">
                    <div>
                        <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider font-mono">Pendentes / Feitos</p>
                        <h3 class="text-3xl font-heading font-black text-slate-900 mt-1">
                            {{ $statusCounts['pending'] ?? 0 }} <span class="text-slate-300 text-xl font-normal">/</span> {{ $statusCounts['completed'] ?? 0 }}
                        </h3>
                        <div class="flex items-center space-x-2 text-2xs text-slate-500 mt-1">
                            <span class="text-amber-600 font-bold">{{ $statusCounts['pending'] ?? 0 }} pendentes</span>
                            <span>•</span>
                            <span class="text-emerald-600 font-bold">{{ $statusCounts['completed'] ?? 0 }} finalizados</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-100 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Próximos Pacientes Agendados -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/40">
                    <div>
                        <h3 class="font-heading font-extrabold text-base text-slate-900">Próximos Pacientes Agendados</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Consultas confirmadas a partir deste momento com triagem clínica prévia por Inteligência Artificial.</p>
                    </div>
                    <a href="{{ route('app.appointments.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-800 transition inline-flex items-center gap-1">
                        <span>Ver todos os agendamentos</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50/75 text-slate-500 uppercase text-[11px] font-mono">
                            <tr>
                                <th class="px-6 py-3.5 text-left font-semibold">Data / Horário</th>
                                <th class="px-6 py-3.5 text-left font-semibold">Paciente & Contato</th>
                                <th class="px-6 py-3.5 text-left font-semibold">Queixa Clínica / Triagem</th>
                                <th class="px-6 py-3.5 text-left font-semibold">Status</th>
                                <th class="px-6 py-3.5 text-right font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($nextAppointments as $appointment)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $appointment->scheduled_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-sky-600 font-extrabold font-mono mt-0.5">{{ $appointment->scheduled_at->format('H:i') }} h</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $appointment->patient_name }}</div>
                                        <div class="text-xs text-slate-500 flex items-center space-x-2 mt-0.5">
                                            <span>{{ $appointment->patient_phone }}</span>
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $appointment->patient_phone) }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 font-bold" title="Abrir conversa no WhatsApp">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.974.53 1.776.814 2.8.814 3.182 0 5.769-2.587 5.769-5.766.001-3.18-2.585-5.766-5.768-5.766zm-9.031 5.766c0-4.97 4.03-9 9-9s9 4.03 9 9c0 4.97-4.03 9-9 9-1.57 0-3.04-.4-4.32-1.12l-4.68 1.23 1.25-4.57c-.79-1.32-1.25-2.85-1.25-4.54z"/></svg>
                                                <span>WhatsApp</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-slate-800 font-medium max-w-xs truncate" title="{{ $appointment->triageRecord?->raw_complaint }}">
                                            {{ $appointment->triageRecord?->raw_complaint ?? 'Sem triagem detalhada' }}
                                        </div>
                                        @if ($appointment->triageRecord)
                                            <div class="flex items-center space-x-2 text-2xs mt-1 flex-wrap gap-y-1">
                                                @if ($appointment->triageRecord->urgency_level === 'high')
                                                    <span class="px-2 py-0.5 bg-rose-100 text-rose-800 rounded-md font-extrabold border border-rose-200/60">Urgência Alta</span>
                                                @elseif ($appointment->triageRecord->urgency_level === 'medium')
                                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-md font-extrabold border border-amber-200/60">Urgência Média</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-bold border border-slate-200/60">Urgência Baixa</span>
                                                @endif

                                                @if ($appointment->triageRecord->pain_level > 0)
                                                    <span class="text-slate-500 font-semibold font-mono">Dor: {{ $appointment->triageRecord->pain_level }}/10</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($appointment->status === 'confirmed')
                                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full text-xs font-bold">Confirmado</span>
                                        @elseif ($appointment->status === 'pending')
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200/80 rounded-full text-xs font-bold">Pendente</span>
                                        @elseif ($appointment->status === 'completed')
                                            <span class="px-2.5 py-1 bg-sky-50 text-sky-700 border border-sky-200/80 rounded-full text-xs font-bold">Concluído</span>
                                        @elseif ($appointment->status === 'no_show')
                                            <span class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200/80 rounded-full text-xs font-bold">Não Compareceu</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200/80 rounded-full text-xs font-bold">Cancelado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                        <form method="POST" action="{{ route('app.appointments.status', $appointment) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if ($appointment->status !== 'confirmed')
                                                <input type="hidden" name="status" value="confirmed" />
                                                <button type="submit" class="text-xs bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-3 py-1.5 rounded-xl transition shadow-2xs">
                                                    Confirmar
                                                </button>
                                            @else
                                                <input type="hidden" name="status" value="completed" />
                                                <button type="submit" class="text-xs bg-sky-600 hover:bg-sky-700 text-white font-bold px-3 py-1.5 rounded-xl transition shadow-2xs">
                                                    Concluir
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <p class="font-semibold text-slate-600">Nenhum paciente agendado para os próximos horários.</p>
                                        <p class="text-slate-400 mt-1">Divulgue seu link de agendamento ou configure sua grade semanal.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <a href="{{ route('app.schedule') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-sky-300 hover:shadow-sm transition group flex items-center space-x-4">
                    <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-slate-900 text-sm">Grade de Atendimento</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Dias de expediente e horários</p>
                    </div>
                </a>

                <a href="{{ route('app.appointments.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-emerald-300 hover:shadow-sm transition group flex items-center space-x-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-slate-900 text-sm">Agendamentos & Triagem</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Histórico completo de leads</p>
                    </div>
                </a>

                <a href="{{ route('app.settings.edit') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-indigo-300 hover:shadow-sm transition group flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition shrink-0 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-slate-900 text-sm">Configuração da IA (BYOK)</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Gemini, OpenAI e WhatsApp</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

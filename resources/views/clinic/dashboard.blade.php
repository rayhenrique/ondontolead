<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">
                        {{ $clinic->name }}
                    </h2>
                    @if ($clinic->subscription_status === 'active')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-bold bg-emerald-100 text-emerald-800">Assinatura Ativa</span>
                    @elseif ($clinic->subscription_status === 'trial')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-bold bg-indigo-100 text-indigo-800">Período de Testes</span>
                    @endif
                </div>
                <div class="flex items-center space-x-3 text-xs text-gray-500 mt-1">
                    <span>WhatsApp: <strong>{{ $clinic->whatsapp_number }}</strong></span>
                    <span>•</span>
                    <span>IA BYOK: <strong class="uppercase text-indigo-600">{{ $clinic->ai_provider !== 'none' ? $clinic->ai_provider : 'Desativada (Fallback)' }}</strong></span>
                </div>
            </div>

            <!-- Public Link Box -->
            <div class="bg-indigo-50/70 border border-indigo-200 rounded-lg px-4 py-2 flex items-center space-x-3 text-xs">
                <div>
                    <span class="text-indigo-600 font-semibold block">Sua Página de Agendamento:</span>
                    <a href="{{ url('/' . $clinic->slug) }}" target="_blank" class="font-mono text-indigo-800 hover:underline">
                        {{ url('/' . $clinic->slug) }}
                    </a>
                </div>
                <a href="{{ url('/' . $clinic->slug) }}" target="_blank" class="p-1.5 bg-white text-indigo-600 rounded hover:bg-indigo-50 border border-indigo-200" title="Abrir página pública">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Onboarding Alert if schedule is missing -->
            @if (! $hasSchedule)
                <div class="p-5 bg-amber-50 border-l-4 border-amber-500 rounded-lg shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <h4 class="font-bold text-sm text-amber-900">Grade de atendimento não configurada</h4>
                            <p class="text-xs text-amber-700 mt-0.5">Sua clínica ainda não ativou horários de atendimento na grade semanal. Os pacientes não conseguirão agendar horários até que você configure seus dias disponíveis.</p>
                        </div>
                    </div>
                    <a href="{{ route('app.schedule') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-md uppercase tracking-wider shrink-0 transition">
                        Configurar Grade &rarr;
                    </a>
                </div>
            @endif

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Hoje -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Agendados Hoje</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $todayCount }}</h3>
                        <p class="text-xs text-indigo-600 mt-1 font-medium">Pacientes aguardados</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>

                <!-- Próximos 7 dias -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Próximos 7 Dias</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $upcomingWeekCount }}</h3>
                        <p class="text-xs text-emerald-600 mt-1 font-medium">Fluxo confirmado</p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>

                <!-- Mês atual -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total do Mês</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $monthCount }}</h3>
                        <p class="text-xs text-gray-500 mt-1 font-medium">Agendamentos captados</p>
                    </div>
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>

                <!-- Status rápidos -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pendentes & Concluídos</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $statusCounts['pending'] ?? 0 }} / {{ $statusCounts['completed'] ?? 0 }}</h3>
                        <div class="flex items-center space-x-2 text-2xs text-gray-500 mt-1">
                            <span class="text-amber-600 font-semibold">{{ $statusCounts['pending'] ?? 0 }} pendentes</span>
                            <span>•</span>
                            <span class="text-emerald-600 font-semibold">{{ $statusCounts['completed'] ?? 0 }} finalizados</span>
                        </div>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Próximos Pacientes Agendados -->
            <div class="bg-white rounded-xl shadow-xs border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Próximos Pacientes Agendados</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Consultas agendadas a partir deste momento com triagem clínica prévia.</p>
                    </div>
                    <a href="{{ route('app.appointments.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Ver todos os agendamentos &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium">Data / Horário</th>
                                <th class="px-6 py-3 text-left font-medium">Paciente & Contato</th>
                                <th class="px-6 py-3 text-left font-medium">Queixa / Urgência</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-right font-medium">Ações Rápidas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($nextAppointments as $appointment)
                                <tr class="hover:bg-gray-50/75 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $appointment->scheduled_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-indigo-600 font-semibold">{{ $appointment->scheduled_at->format('H:i') }} h</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900">{{ $appointment->patient_name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center space-x-1.5 mt-0.5">
                                            <span>{{ $appointment->patient_phone }}</span>
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $appointment->patient_phone) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 font-medium" title="Abrir conversa no WhatsApp">
                                                (WhatsApp)
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-800 font-medium max-w-xs truncate">
                                            {{ $appointment->triageRecord?->raw_complaint ?? 'Sem triagem registrada' }}
                                        </div>
                                        @if ($appointment->triageRecord)
                                            <div class="flex items-center space-x-2 text-2xs mt-1">
                                                @if ($appointment->triageRecord->urgency_level === 'high')
                                                    <span class="px-1.5 py-0.5 bg-rose-100 text-rose-800 rounded font-bold">Urgência Alta</span>
                                                @elseif ($appointment->triageRecord->urgency_level === 'medium')
                                                    <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 rounded font-bold">Urgência Média</span>
                                                @else
                                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded font-bold">Urgência Baixa</span>
                                                @endif

                                                @if ($appointment->triageRecord->pain_level > 0)
                                                    <span class="text-gray-500">Dor: {{ $appointment->triageRecord->pain_level }}/10</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($appointment->status === 'confirmed')
                                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-xs font-semibold">Confirmado</span>
                                        @elseif ($appointment->status === 'pending')
                                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">Pendente</span>
                                        @elseif ($appointment->status === 'completed')
                                            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Concluído</span>
                                        @elseif ($appointment->status === 'no_show')
                                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Não Compareceu</span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 rounded-full text-xs font-semibold">Cancelado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                        <form method="POST" action="{{ route('app.appointments.status', $appointment) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if ($appointment->status !== 'confirmed')
                                                <input type="hidden" name="status" value="confirmed" />
                                                <button type="submit" class="text-xs bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold px-2.5 py-1 rounded transition">
                                                    Confirmar
                                                </button>
                                            @else
                                                <input type="hidden" name="status" value="completed" />
                                                <button type="submit" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold px-2.5 py-1 rounded transition">
                                                    Concluir
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-xs">
                                        Nenhum paciente agendado para os próximos horários.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <a href="{{ route('app.schedule') }}" class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-indigo-300 transition group flex items-center space-x-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Grade de Atendimento</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Dias e horários livres</p>
                    </div>
                </a>

                <a href="{{ route('app.appointments.index') }}" class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-indigo-300 transition group flex items-center space-x-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Agendamentos & Triagem</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Histórico completo de leads</p>
                    </div>
                </a>

                <a href="{{ route('app.settings.edit') }}" class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-indigo-300 transition group flex items-center space-x-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Configuração da IA (BYOK)</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Gemini, OpenAI e WhatsApp</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

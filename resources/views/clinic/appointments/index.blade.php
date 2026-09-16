<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Agendamentos & Triagens</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Gerencie consultas, acompanhe triagens com IA e atualize o status dos pacientes da {{ $clinic->name }}.
                </p>
            </div>
            <div>
                <a href="{{ route('app.schedule') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 font-semibold text-sm rounded-xl hover:bg-indigo-100 transition shadow-sm">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Configurar Horários & Bloqueios
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-[calc(100vh-140px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Status Tabs -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-2 flex flex-wrap gap-1.5 sm:gap-2">
                @php
                    $tabs = [
                        '' => ['label' => 'Todos', 'count' => $totalCount],
                        'pending' => ['label' => 'Pendentes', 'count' => $statusCounts['pending'] ?? 0],
                        'confirmed' => ['label' => 'Confirmados', 'count' => $statusCounts['confirmed'] ?? 0],
                        'completed' => ['label' => 'Concluídos', 'count' => $statusCounts['completed'] ?? 0],
                        'canceled' => ['label' => 'Cancelados', 'count' => $statusCounts['canceled'] ?? 0],
                        'no_show' => ['label' => 'Não compareceu', 'count' => $statusCounts['no_show'] ?? 0],
                    ];
                @endphp

                @foreach ($tabs as $key => $meta)
                    @php
                        $isActive = $status === $key;
                        $query = request()->except('page');
                        if ($key === '') {
                            unset($query['status']);
                        } else {
                            $query['status'] = $key;
                        }
                        $tabUrl = route('app.appointments.index', $query);
                    @endphp
                    <a href="{{ $tabUrl }}"
                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition {{ $isActive ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>{{ $meta['label'] }}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $isActive ? 'bg-indigo-700 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $meta['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Filters Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <form method="GET" action="{{ route('app.appointments.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                    @if ($status !== '')
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif

                    <div class="sm:col-span-6 lg:col-span-5">
                        <label for="search" class="block text-xs font-semibold text-slate-600 mb-1">Buscar por Paciente ou Telefone</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   id="search"
                                   name="search"
                                   value="{{ $search }}"
                                   placeholder="Nome ou telefone..."
                                   class="pl-9 w-full text-sm border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                    </div>

                    <div class="sm:col-span-4 lg:col-span-4">
                        <label for="date" class="block text-xs font-semibold text-slate-600 mb-1">Filtrar por Data</label>
                        <input type="date"
                               id="date"
                               name="date"
                               value="{{ $date }}"
                               class="w-full text-sm border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>

                    <div class="sm:col-span-2 lg:col-span-3 flex items-center gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-800 transition shadow-sm flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filtrar
                        </button>

                        @if ($search !== '' || $date !== '' || $status !== '')
                            <a href="{{ route('app.appointments.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs sm:text-sm font-medium hover:bg-slate-200 transition" title="Limpar Filtros">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Appointments List -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                @if ($appointments->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Nenhum agendamento encontrado</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                            @if ($search !== '' || $date !== '' || $status !== '')
                                Não há agendamentos que correspondam aos filtros selecionados. Tente ajustar os parâmetros.
                            @else
                                Ainda não há consultas agendadas. Compartilhe o link de agendamento público da sua clínica!
                            @endif
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider text-xs">
                                <tr>
                                    <th class="px-6 py-4">Data & Horário</th>
                                    <th class="px-6 py-4">Paciente</th>
                                    <th class="px-6 py-4">Triagem & Urgência</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($appointments as $appointment)
                                    @php
                                        $triage = $appointment->triageRecord;
                                        $cleanPhone = preg_replace('/\D/', '', $appointment->patient_phone ?? '');
                                        $waUrl = $cleanPhone ? "https://wa.me/55{$cleanPhone}?text=" . urlencode("Olá {$appointment->patient_name}, confirmamos seu agendamento na {$clinic->name}.") : null;
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold text-slate-900">
                                                {{ $appointment->scheduled_at ? $appointment->scheduled_at->format('d/m/Y') : 'Data a definir' }}
                                            </div>
                                            <div class="text-xs text-indigo-600 font-medium">
                                                {{ $appointment->scheduled_at ? $appointment->scheduled_at->format('H:i') . ' hrs' : '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-slate-800">{{ $appointment->patient_name }}</div>
                                            <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                                <span>{{ $appointment->patient_phone }}</span>
                                                @if ($waUrl)
                                                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:text-emerald-700 inline-flex items-center" title="Abrir WhatsApp">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.966.54 1.761.88 2.796.88 3.183 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.077-2.257-.565-1.921-.791-3.158-2.73-3.254-2.859-.096-.129-.773-1.028-.773-1.96 0-.933.488-1.393.662-1.583.174-.19.38-.238.507-.238.127 0 .254.001.365.006.118.005.277-.045.433.332.162.392.553 1.349.602 1.448.049.099.082.215.016.345-.065.131-.098.213-.196.327-.098.115-.206.257-.294.345-.1.1-.205.209-.089.408.116.199.516.852 1.107 1.378.761.678 1.403.888 1.602.987.199.099.316.083.433-.051.117-.134.502-.584.636-.784.134-.2.268-.167.45-.1.182.067 1.156.545 1.354.644.198.099.33.149.379.233.049.084.049.489-.095.894z"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($triage)
                                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                                    @php
                                                        $urgencyBadges = [
                                                            'urgent' => 'bg-red-100 text-red-800 border-red-200',
                                                            'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                            'medium' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                            'low' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                        ];
                                                        $urgencyLabels = [
                                                            'urgent' => 'Urgente',
                                                            'high' => 'Alta',
                                                            'medium' => 'Média',
                                                            'low' => 'Baixa',
                                                        ];
                                                        $urgencyKey = $triage->urgency_level ?? 'low';
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $urgencyBadges[$urgencyKey] ?? 'bg-slate-100 text-slate-800' }}">
                                                        Urgência {{ $urgencyLabels[$urgencyKey] ?? ucfirst($urgencyKey) }}
                                                    </span>

                                                    @if ($triage->pain_level !== null)
                                                        <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">
                                                            Dor: <strong>{{ $triage->pain_level }}/10</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                @if ($triage->suggested_procedure)
                                                    <div class="text-xs text-indigo-700 font-semibold truncate max-w-xs">
                                                        {{ $triage->suggested_procedure }}
                                                    </div>
                                                @endif

                                                <details class="group mt-1 cursor-pointer">
                                                    <summary class="text-xs text-slate-500 hover:text-slate-800 font-medium list-none flex items-center gap-1">
                                                        <span class="group-open:hidden">Ver resumo IA & queixa &darr;</span>
                                                        <span class="hidden group-open:inline">Recolher &uarr;</span>
                                                    </summary>
                                                    <div class="mt-2 p-2.5 bg-slate-50 rounded-lg text-xs text-slate-700 border border-slate-200 space-y-1.5 max-w-md">
                                                        @if ($triage->ai_summary)
                                                            <div>
                                                                <strong class="text-slate-900 block font-semibold">Resumo IA:</strong>
                                                                <p>{{ $triage->ai_summary }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($triage->raw_complaint)
                                                            <div>
                                                                <strong class="text-slate-900 block font-semibold">Queixa informada:</strong>
                                                                <p class="italic text-slate-600">"{{ $triage->raw_complaint }}"</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </details>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Sem triagem IA</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusBadges = [
                                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'canceled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                    'no_show' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Pendente',
                                                    'confirmed' => 'Confirmado',
                                                    'completed' => 'Concluído',
                                                    'canceled' => 'Cancelado',
                                                    'no_show' => 'Não compareceu',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusBadges[$appointment->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                                {{ $statusLabels[$appointment->status] ?? $appointment->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="inline-flex items-center gap-2">
                                                @if ($waUrl)
                                                    <a href="{{ $waUrl }}"
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       class="p-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition"
                                                       title="Contatar via WhatsApp">
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.966.54 1.761.88 2.796.88 3.183 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.077-2.257-.565-1.921-.791-3.158-2.73-3.254-2.859-.096-.129-.773-1.028-.773-1.96 0-.933.488-1.393.662-1.583.174-.19.38-.238.507-.238.127 0 .254.001.365.006.118.005.277-.045.433.332.162.392.553 1.349.602 1.448.049.099.082.215.016.345-.065.131-.098.213-.196.327-.098.115-.206.257-.294.345-.1.1-.205.209-.089.408.116.199.516.852 1.107 1.378.761.678 1.403.888 1.602.987.199.099.316.083.433-.051.117-.134.502-.584.636-.784.134-.2.268-.167.45-.1.182.067 1.156.545 1.354.644.198.099.33.149.379.233.049.084.049.489-.095.894z"/>
                                                        </svg>
                                                    </a>
                                                @endif

                                                <!-- Status Update Dropdown Form -->
                                                <form method="POST" action="{{ route('app.appointments.status', $appointment) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status"
                                                            onchange="this.form.submit()"
                                                            class="text-xs font-semibold rounded-lg border-slate-300 py-1.5 pl-2.5 pr-6 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                                        <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                                        <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                                        <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Concluído</option>
                                                        <option value="canceled" {{ $appointment->status === 'canceled' ? 'selected' : '' }}>Cancelado</option>
                                                        <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>Não compareceu</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($appointments->hasPages())
                        <div class="p-4 border-t border-slate-100">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

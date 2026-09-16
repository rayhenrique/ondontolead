<div class="space-y-8">
    @if ($feedbackMessage)
        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded shadow-sm flex items-center justify-between">
            <span>{{ $feedbackMessage }}</span>
            <button wire:click="$set('feedbackMessage', null)" class="text-emerald-500 hover:text-emerald-700 text-xs font-bold">&times;</button>
        </div>
    @endif

    <!-- Grade Semanal -->
    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6">
        <div class="border-b border-gray-100 pb-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Grade Semanal de Atendimento</h3>
                <p class="text-xs text-gray-500 mt-0.5">Defina os dias ativos, horários de expediente, intervalos e duração de cada consulta.</p>
            </div>
            <button wire:click="saveSchedules" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                <span wire:loading.remove wire:target="saveSchedules">Salvar Horários</span>
                <span wire:loading wire:target="saveSchedules">Salvando...</span>
            </button>
        </div>

        <div class="space-y-4">
            @foreach ($schedules as $day => $schedule)
                <div class="p-4 rounded-lg border {{ $schedule['is_active'] ? 'border-indigo-100 bg-white shadow-xs' : 'border-gray-200 bg-gray-50/75' }} transition">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        
                        <!-- Dia e Toggle -->
                        <div class="sm:col-span-3 flex items-center space-x-3">
                            <input type="checkbox" id="day_{{ $day }}" wire:model.live="schedules.{{ $day }}.is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                            <label for="day_{{ $day }}" class="text-sm font-semibold {{ $schedule['is_active'] ? 'text-gray-900' : 'text-gray-400' }} cursor-pointer">
                                {{ $dayNames[$day] }}
                            </label>
                        </div>

                        @if ($schedule['is_active'])
                            <!-- Horário Expediente -->
                            <div class="sm:col-span-4 flex items-center space-x-2">
                                <div>
                                    <label class="block text-2xs text-gray-500 uppercase tracking-wider mb-0.5">Início</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.start_time" class="text-xs rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2" />
                                </div>
                                <span class="text-gray-400 mt-3 text-xs">às</span>
                                <div>
                                    <label class="block text-2xs text-gray-500 uppercase tracking-wider mb-0.5">Término</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.end_time" class="text-xs rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2" />
                                </div>
                            </div>

                            <!-- Intervalo / Almoço -->
                            <div class="sm:col-span-3 flex items-center space-x-2">
                                <div>
                                    <label class="block text-2xs text-gray-500 uppercase tracking-wider mb-0.5">Intervalo</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.break_start" placeholder="12:00" class="text-xs rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2" />
                                </div>
                                <span class="text-gray-400 mt-3 text-xs">às</span>
                                <div>
                                    <label class="block text-2xs text-gray-500 uppercase tracking-wider mb-0.5">Retorno</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.break_end" placeholder="13:00" class="text-xs rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2" />
                                </div>
                            </div>

                            <!-- Duração Slot -->
                            <div class="sm:col-span-2">
                                <label class="block text-2xs text-gray-500 uppercase tracking-wider mb-0.5">Duração (min)</label>
                                <select wire:model.defer="schedules.{{ $day }}.slot_duration_minutes" class="w-full text-xs rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">
                                    <option value="15">15 min</option>
                                    <option value="20">20 min</option>
                                    <option value="30">30 min</option>
                                    <option value="45">45 min</option>
                                    <option value="60">60 min</option>
                                </select>
                            </div>
                        @else
                            <div class="sm:col-span-9 text-xs text-gray-400 italic py-2">
                                Não há atendimento neste dia da semana.
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="saveSchedules" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                <span wire:loading.remove wire:target="saveSchedules">Salvar Horários da Grade</span>
                <span wire:loading wire:target="saveSchedules">Salvando...</span>
            </button>
        </div>
    </div>

    <!-- Datas Bloqueadas -->
    <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Datas Bloqueadas (Feriados e Férias)</h3>
            <p class="text-xs text-gray-500 mt-0.5">Os pacientes não poderão agendar horários nas datas cadastradas aqui.</p>
        </div>

        <!-- Formulário para adicionar bloqueio -->
        <form wire:submit="addBlockedDate" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                <div class="sm:col-span-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Data a Bloquear *</label>
                    <input type="date" wire:model="newBlockedDate" required min="{{ date('Y-m-d') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('newBlockedDate') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-6">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Motivo do Bloqueio</label>
                    <input type="text" wire:model="newBlockedReason" placeholder="Ex: Feriado municipal, Congresso, Férias coletivas" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('newBlockedReason') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" wire:loading.attr="disabled" class="w-full bg-gray-800 text-white text-xs font-semibold py-2.5 px-4 rounded-md hover:bg-gray-700 transition">
                        <span wire:loading.remove wire:target="addBlockedDate">Adicionar</span>
                        <span wire:loading wire:target="addBlockedDate">...</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Lista de Datas Bloqueadas -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Data</th>
                        <th class="px-4 py-3 text-left font-medium">Motivo</th>
                        <th class="px-4 py-3 text-right font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($blockedDates as $item)
                        <tr class="hover:bg-gray-50/75 transition">
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                                {{ $item['formatted_date'] }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $item['reason'] ?: 'Bloqueio administrativo' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <button type="button" wire:click="removeBlockedDate({{ $item['id'] }})" wire:confirm="Deseja liberar esta data para agendamentos novamente?" class="text-xs font-medium text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded transition">
                                    Remover Bloqueio
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-400 text-xs">
                                Nenhuma data bloqueada cadastrada para os próximos dias.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

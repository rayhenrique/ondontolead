<div class="space-y-8">
    @if ($feedbackMessage)
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $feedbackMessage }}</span>
            </div>
            <button wire:click="$set('feedbackMessage', null)" class="text-emerald-600 hover:text-emerald-800 text-sm font-bold">&times;</button>
        </div>
    @endif

    <!-- Grade Semanal -->
    <div class="bg-white shadow-xs rounded-2xl border border-slate-200/80 p-6 sm:p-8">
        <div class="border-b border-slate-100 pb-5 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-heading font-black text-lg text-slate-900">Grade Semanal de Atendimento</h3>
                <p class="text-xs text-slate-500 mt-0.5">Defina os dias ativos, turnos de expediente, intervalos e duração de cada consulta.</p>
            </div>
            <button wire:click="saveSchedules" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider transition shadow-sm shadow-emerald-500/20">
                <span wire:loading.remove wire:target="saveSchedules">Salvar Horários da Grade</span>
                <span wire:loading wire:target="saveSchedules">Salvando...</span>
            </button>
        </div>

        <div class="space-y-3.5">
            @foreach ($schedules as $day => $schedule)
                <div class="p-4 rounded-xl border {{ $schedule['is_active'] ? 'border-sky-200 bg-sky-50/30 shadow-2xs' : 'border-slate-200/70 bg-slate-50/50' }} transition">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        
                        <!-- Dia e Toggle -->
                        <div class="sm:col-span-3 flex items-center space-x-3">
                            <input type="checkbox" id="day_{{ $day }}" wire:model.live="schedules.{{ $day }}.is_active" class="rounded-lg border-slate-300 text-emerald-500 shadow-2xs focus:ring-emerald-500" />
                            <label for="day_{{ $day }}" class="text-sm font-bold {{ $schedule['is_active'] ? 'text-slate-900' : 'text-slate-400' }} cursor-pointer">
                                {{ $dayNames[$day] }}
                            </label>
                        </div>

                        @if ($schedule['is_active'])
                            <!-- Horário Expediente -->
                            <div class="sm:col-span-4 flex items-center gap-2">
                                <div class="flex-1 min-w-0">
                                    <label class="block text-[10px] font-mono text-slate-400 uppercase tracking-wider font-extrabold mb-0.5">Início</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.start_time" class="w-full text-xs font-mono font-semibold rounded-xl border-slate-200 shadow-2xs focus:border-sky-500 focus:ring-sky-500 py-1.5 px-2 bg-white" />
                                </div>
                                <span class="text-slate-400 mt-4 text-xs font-mono shrink-0">até</span>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-[10px] font-mono text-slate-400 uppercase tracking-wider font-extrabold mb-0.5">Término</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.end_time" class="w-full text-xs font-mono font-semibold rounded-xl border-slate-200 shadow-2xs focus:border-sky-500 focus:ring-sky-500 py-1.5 px-2 bg-white" />
                                </div>
                            </div>

                            <!-- Intervalo / Almoço -->
                            <div class="sm:col-span-3 flex items-center gap-2">
                                <div class="flex-1 min-w-0">
                                    <label class="block text-[10px] font-mono text-slate-400 uppercase tracking-wider font-extrabold mb-0.5">Intervalo</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.break_start" placeholder="12:00" class="w-full text-xs font-mono font-semibold rounded-xl border-slate-200 shadow-2xs focus:border-sky-500 focus:ring-sky-500 py-1.5 px-2 bg-white" />
                                </div>
                                <span class="text-slate-400 mt-4 text-xs font-mono shrink-0">até</span>
                                <div class="flex-1 min-w-0">
                                    <label class="block text-[10px] font-mono text-slate-400 uppercase tracking-wider font-extrabold mb-0.5">Retorno</label>
                                    <input type="time" wire:model.defer="schedules.{{ $day }}.break_end" placeholder="13:00" class="w-full text-xs font-mono font-semibold rounded-xl border-slate-200 shadow-2xs focus:border-sky-500 focus:ring-sky-500 py-1.5 px-2 bg-white" />
                                </div>
                            </div>

                            <!-- Duração Slot -->
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] font-mono text-slate-400 uppercase tracking-wider font-extrabold mb-0.5">Duração (min)</label>
                                <select wire:model.defer="schedules.{{ $day }}.slot_duration_minutes" class="w-full text-xs font-bold rounded-xl border-slate-200 shadow-2xs focus:border-sky-500 focus:ring-sky-500 py-1.5 px-2.5 bg-white">
                                    <option value="15">15 min</option>
                                    <option value="20">20 min</option>
                                    <option value="30">30 min</option>
                                    <option value="45">45 min</option>
                                    <option value="60">60 min</option>
                                </select>
                            </div>
                        @else
                            <div class="sm:col-span-9 text-xs text-slate-400 italic py-2">
                                Não há atendimento neste dia da semana.
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="saveSchedules" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider transition shadow-sm shadow-emerald-500/20">
                <span wire:loading.remove wire:target="saveSchedules">Salvar Horários da Grade</span>
                <span wire:loading wire:target="saveSchedules">Salvando...</span>
            </button>
        </div>
    </div>

    <!-- Datas Bloqueadas -->
    <div class="bg-white shadow-xs rounded-2xl border border-slate-200/80 p-6 sm:p-8">
        <div class="border-b border-slate-100 pb-4 mb-6">
            <h3 class="font-heading font-black text-lg text-slate-900">Datas Bloqueadas (Feriados, Férias e Recessos)</h3>
            <p class="text-xs text-slate-500 mt-0.5">Os pacientes não poderão agendar horários nas datas cadastradas nesta lista.</p>
        </div>

        <!-- Formulário para adicionar bloqueio -->
        <form wire:submit="addBlockedDate" class="bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                <div class="sm:col-span-4">
                    <label class="block text-[11px] font-mono font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Data a Bloquear *</label>
                    <input type="date" wire:model="newBlockedDate" required min="{{ date('Y-m-d') }}" class="w-full text-xs sm:text-sm rounded-xl border-slate-200 shadow-2xs focus:border-sky-500 focus:ring-sky-500 bg-white" />
                    @error('newBlockedDate') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-6">
                    <label class="block text-[11px] font-mono font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Motivo do Bloqueio</label>
                    <input type="text" wire:model="newBlockedReason" placeholder="Ex: Feriado municipal, Congresso, Férias coletivas" class="w-full text-xs sm:text-sm rounded-xl border-slate-200 shadow-2xs focus:border-sky-500 focus:ring-sky-500 bg-white" />
                    @error('newBlockedReason') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" wire:loading.attr="disabled" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition shadow-xs">
                        <span wire:loading.remove wire:target="addBlockedDate">Adicionar</span>
                        <span wire:loading wire:target="addBlockedDate">...</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Lista de Datas Bloqueadas -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[11px] font-mono">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Data</th>
                        <th class="px-5 py-3 text-left font-semibold">Motivo</th>
                        <th class="px-5 py-3 text-right font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($blockedDates as $item)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap font-bold text-slate-900 font-mono text-xs">
                                {{ $item['formatted_date'] }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $item['reason'] ?: 'Bloqueio administrativo' }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                <button type="button" wire:click="removeBlockedDate({{ $item['id'] }})" wire:confirm="Deseja liberar esta data para agendamentos novamente?" class="text-xs font-bold text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-xl border border-rose-200/60 transition shadow-2xs">
                                    Remover Bloqueio
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-slate-400 text-xs">
                                Nenhuma data bloqueada cadastrada para os próximos dias.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

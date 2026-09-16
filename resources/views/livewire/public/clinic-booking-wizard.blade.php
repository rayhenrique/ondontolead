<div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
    <!-- Stepper Header -->
    <div class="bg-slate-900 text-white p-6 sm:p-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Agendamento Online</span>
                <h3 class="text-xl font-extrabold text-white mt-0.5">
                    @if ($currentStep === 1) Identificação do Paciente
                    @elseif ($currentStep === 2) Avaliação de Sintomas & Dor
                    @elseif ($currentStep === 3) Resultado da Triagem Clínica
                    @elseif ($currentStep === 4) Seleção de Horário Disponível
                    @elseif ($currentStep === 5) Agendamento Concluído!
                    @endif
                </h3>
            </div>
            <div class="text-right">
                <span class="text-xs text-slate-400">Etapa</span>
                <div class="text-lg font-bold text-white">{{ $currentStep }} <span class="text-slate-500 font-normal">/ 4</span></div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-indigo-500 h-2 transition-all duration-500 ease-out"
                 style="width: {{ $currentStep === 5 ? 100 : ($currentStep * 25) }}%"></div>
        </div>

        <!-- Steps Icons -->
        <div class="grid grid-cols-4 gap-2 mt-4 text-[11px] font-medium text-slate-400">
            <div class="flex items-center gap-1.5 {{ $currentStep >= 1 ? 'text-emerald-400 font-semibold' : '' }}">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $currentStep >= 1 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-slate-800' }}">1</span>
                <span class="hidden sm:inline">Dados</span>
            </div>
            <div class="flex items-center gap-1.5 {{ $currentStep >= 2 ? 'text-emerald-400 font-semibold' : '' }}">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $currentStep >= 2 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-slate-800' }}">2</span>
                <span class="hidden sm:inline">Sintomas</span>
            </div>
            <div class="flex items-center gap-1.5 {{ $currentStep >= 3 ? 'text-emerald-400 font-semibold' : '' }}">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $currentStep >= 3 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-slate-800' }}">3</span>
                <span class="hidden sm:inline">Triagem</span>
            </div>
            <div class="flex items-center gap-1.5 {{ $currentStep >= 4 ? 'text-emerald-400 font-semibold' : '' }}">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs {{ $currentStep >= 4 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-slate-800' }}">4</span>
                <span class="hidden sm:inline">Horário</span>
            </div>
        </div>
    </div>

    <!-- Wizard Content Body -->
    <div class="p-6 sm:p-8">

        <!-- Error Message Alert -->
        @if ($errorMessage)
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 text-rose-800 text-sm">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <strong class="font-semibold block">Atenção</strong>
                    <span>{{ $errorMessage }}</span>
                </div>
            </div>
        @endif

        <!-- ================= STEP 1: DADOS DO PACIENTE ================= -->
        @if ($currentStep === 1)
            <form wire:submit.prevent="goToStep2" class="space-y-6">
                <div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Informe seus dados para contato</h4>
                    <p class="text-xs text-slate-500">Usaremos seu WhatsApp para confirmar os detalhes do agendamento.</p>
                </div>

                <div class="space-y-4">
                    <!-- Nome -->
                    <div>
                        <label for="patient_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Seu Nome Completo *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   id="patient_name"
                                   wire:model.defer="patient_name"
                                   placeholder="Ex: Maria Clara dos Santos"
                                   required
                                   class="pl-11 w-full text-sm border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 shadow-sm py-3">
                        </div>
                        @error('patient_name')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label for="patient_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            WhatsApp / Celular com DDD *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.966.54 1.761.88 2.796.88 3.183 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.077-2.257-.565-1.921-.791-3.158-2.73-3.254-2.859-.096-.129-.773-1.028-.773-1.96 0-.933.488-1.393.662-1.583.174-.19.38-.238.507-.238.127 0 .254.001.365.006.118.005.277-.045.433.332.162.392.553 1.349.602 1.448.049.099.082.215.016.345-.065.131-.098.213-.196.327-.098.115-.206.257-.294.345-.1.1-.205.209-.089.408.116.199.516.852 1.107 1.378.761.678 1.403.888 1.602.987.199.099.316.083.433-.051.117-.134.502-.584.636-.784.134-.2.268-.167.45-.1.182.067 1.156.545 1.354.644.198.099.33.149.379.233.049.084.049.489-.095.894z"/>
                                </svg>
                            </div>
                            <input type="tel"
                                   id="patient_phone"
                                   wire:model.defer="patient_phone"
                                   placeholder="(11) 99999-9999"
                                   required
                                   class="pl-11 w-full text-sm border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 shadow-sm py-3">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Insira seu DDD e número com 9 dígitos.</p>
                        @error('patient_phone')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <span class="text-xs text-slate-400 flex items-center gap-1">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Ambiente seguro e protegido
                    </span>
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                        Continuar para Avaliação &rarr;
                    </button>
                </div>
            </form>
        @endif

        <!-- ================= STEP 2: SINTOMAS & QUEIXA ================= -->
        @if ($currentStep === 2)
            <form wire:submit.prevent="goToStep3" class="space-y-6">
                <div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Qual o motivo da sua consulta?</h4>
                    <p class="text-xs text-slate-500">Conte-nos seus sintomas para que a equipe da clínica se prepare para o seu atendimento.</p>
                </div>

                <!-- Queixa Principal -->
                <div>
                    <label for="complaint" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Descreva o que está sentindo ou o tratamento desejado *
                    </label>
                    <textarea id="complaint"
                              wire:model.defer="complaint"
                              rows="3"
                              required
                              placeholder="Ex: Sinto dor aguda ao mastigar no lado direito, ou gostaria de fazer uma avaliação para implante/clareamento..."
                              class="w-full text-sm border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 shadow-sm"></textarea>
                    @error('complaint')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Escala de Dor -->
                <div x-data="{ pain: @entangle('pain_level') }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Nível de dor ou incômodo atual (0 a 10) *
                        </label>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full"
                              :class="{
                                  'bg-emerald-100 text-emerald-800': pain <= 3,
                                  'bg-amber-100 text-amber-800': pain > 3 && pain <= 6,
                                  'bg-orange-100 text-orange-800': pain > 6 && pain <= 8,
                                  'bg-rose-100 text-rose-800': pain > 8
                              }"
                              x-text="pain == 0 ? 'Sem dor' : (pain <= 3 ? 'Dor Leve (' + pain + '/10)' : (pain <= 6 ? 'Dor Moderada (' + pain + '/10)' : (pain <= 8 ? 'Dor Intensa (' + pain + '/10)' : 'Dor Extrema (' + pain + '/10)')))">
                        </span>
                    </div>

                    <div class="grid grid-cols-11 gap-1 sm:gap-1.5">
                        @for ($i = 0; $i <= 10; $i++)
                            <button type="button"
                                    wire:click="$set('pain_level', {{ $i }})"
                                    class="py-2.5 text-xs font-bold rounded-lg border transition text-center {{ $pain_level === $i ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                {{ $i }}
                            </button>
                        @endfor
                    </div>
                </div>

                <!-- Sinais e Sintomas Adicionais (Chips) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Você apresenta algum destes sinais? (Opcional)
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition {{ $has_swelling ? 'border-amber-400 bg-amber-50/60' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}">
                            <input type="checkbox" wire:model.defer="has_swelling" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-xs font-medium text-slate-800">Inchaço no rosto ou gengiva</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition {{ $has_bleeding ? 'border-rose-400 bg-rose-50/60' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}">
                            <input type="checkbox" wire:model.defer="has_bleeding" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-medium text-slate-800">Sangramento constante</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition {{ $had_trauma ? 'border-indigo-400 bg-indigo-50/60' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}">
                            <input type="checkbox" wire:model.defer="had_trauma" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-medium text-slate-800">Trauma / Queda recente</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition {{ $has_fever ? 'border-orange-400 bg-orange-50/60' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}">
                            <input type="checkbox" wire:model.defer="has_fever" class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                            <span class="text-xs font-medium text-slate-800">Febre associada</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition sm:col-span-2 {{ $has_breathing_difficulty ? 'border-red-400 bg-red-50/60' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}">
                            <input type="checkbox" wire:model.defer="has_breathing_difficulty" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                            <span class="text-xs font-medium text-slate-800">Dificuldade para engolir, respirar ou abrir a boca</span>
                        </label>
                    </div>
                </div>

                <!-- Histórico Médico -->
                <div>
                    <label for="medical_history" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Alergias a medicamentos ou condições médicas (Opcional)
                    </label>
                    <input type="text"
                           id="medical_history"
                           wire:model.defer="medical_history"
                           placeholder="Ex: Alergia a penicilina, diabetes, hipertensão..."
                           class="w-full text-sm border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <button type="button" wire:click="previousStep" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                        &larr; Voltar
                    </button>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                        <span wire:loading.remove>Gerar Triagem Clínica &rarr;</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Analisando com IA...
                        </span>
                    </button>
                </div>
            </form>
        @endif

        <!-- ================= STEP 3: RESULTADO DA TRIAGEM ================= -->
        @if ($currentStep === 3)
            <div class="space-y-6">
                <div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Avaliação Pré-Clínica Pronta!</h4>
                    <p class="text-xs text-slate-500">Seus sintomas foram analisados e classificados para priorização do atendimento.</p>
                </div>

                <!-- Triage Card -->
                <div class="bg-gradient-to-br from-slate-50 to-slate-100/80 rounded-2xl border border-slate-200 p-6 space-y-4 shadow-sm">
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            @php
                                $badgeStyle = match ($triage_urgency) {
                                    'urgent' => 'bg-rose-100 text-rose-800 border-rose-300',
                                    'high' => 'bg-orange-100 text-orange-800 border-orange-300',
                                    'medium' => 'bg-amber-100 text-amber-800 border-amber-300',
                                    default => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                };
                                $urgencyTitle = match ($triage_urgency) {
                                    'urgent' => 'Urgência Imediata',
                                    'high' => 'Prioridade Alta',
                                    'medium' => 'Prioridade Moderada',
                                    default => 'Consulta Eletiva / Baixa Urgência',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $badgeStyle }}">
                                {{ $urgencyTitle }}
                            </span>

                            @if ($triage_processed_by_ai)
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-800 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Triagem com IA
                                </span>
                            @endif
                        </div>

                        <span class="text-xs text-slate-500">
                            Dor informada: <strong>{{ $pain_level }}/10</strong>
                        </span>
                    </div>

                    <!-- Resumo -->
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Resumo da Avaliação</span>
                        <p class="text-sm text-slate-800 leading-relaxed bg-white p-4 rounded-xl border border-slate-200">
                            {{ $triage_summary }}
                        </p>
                    </div>

                    <!-- Procedimento Sugerido -->
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Procedimento Recomendado</span>
                        <div class="bg-indigo-50 border border-indigo-200 text-indigo-900 font-bold text-sm px-4 py-3 rounded-xl flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $triage_suggested_procedure }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <button type="button" wire:click="previousStep" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                        &larr; Revisar Sintomas
                    </button>

                    <button type="button"
                            wire:click="goToStep4"
                            class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                        Escolher Data & Horário &rarr;
                    </button>
                </div>
            </div>
        @endif

        <!-- ================= STEP 4: DATA & HORÁRIO LIVRE ================= -->
        @if ($currentStep === 4)
            <div class="space-y-6">
                <div>
                    <h4 class="text-base font-bold text-slate-900 mb-1">Escolha o melhor dia e horário</h4>
                    <p class="text-xs text-slate-500">Horários disponíveis em tempo real na agenda da {{ $clinic->name }}.</p>
                </div>

                <!-- Seletor de Data -->
                <div>
                    <label for="selected_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Data da Consulta *
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="date"
                               id="selected_date"
                               wire:model.live="selected_date"
                               min="{{ now()->format('Y-m-d') }}"
                               class="text-sm border-slate-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 shadow-sm py-2.5 px-4 font-semibold">

                        <!-- Fast Date Buttons -->
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    wire:click="$set('selected_date', '{{ now()->format('Y-m-d') }}')"
                                    class="px-3 py-2 text-xs font-bold rounded-lg border transition {{ $selected_date === now()->format('Y-m-d') ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200' }}">
                                Hoje
                            </button>
                            <button type="button"
                                    wire:click="$set('selected_date', '{{ now()->addDay()->format('Y-m-d') }}')"
                                    class="px-3 py-2 text-xs font-bold rounded-lg border transition {{ $selected_date === now()->addDay()->format('Y-m-d') ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200' }}">
                                Amanhã
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grade de Horários Disponíveis -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Horários Livres para {{ \Carbon\Carbon::parse($selected_date)->format('d/m/Y') }} *
                    </label>

                    @if (empty($available_slots))
                        <div class="p-8 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                            <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h5 class="text-sm font-bold text-slate-800">Nenhum horário disponível para esta data</h5>
                            <p class="text-xs text-slate-500 mt-1">A clínica não possui expediente nesta data ou todos os horários já foram agendados. Por favor, selecione outro dia.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                            @foreach ($available_slots as $slot)
                                <button type="button"
                                        wire:click="selectTime('{{ $slot }}')"
                                        class="py-3 px-2 text-center rounded-xl border text-sm font-extrabold transition {{ $selected_time === $slot ? 'bg-emerald-600 text-white border-emerald-600 shadow-md transform scale-105' : 'bg-white text-slate-800 border-slate-200 hover:border-emerald-400 hover:bg-emerald-50/50' }}">
                                    {{ $slot }}
                                </button>
                            @endforeach
                        </div>
                        @error('selected_time')
                            <p class="text-xs text-rose-600 mt-2 font-medium">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Resumo Final Pré-Confirmação -->
                @if ($selected_time)
                    <div class="bg-emerald-50/80 border border-emerald-200 rounded-2xl p-4 text-xs text-emerald-900 space-y-1">
                        <div class="font-bold text-sm text-emerald-950 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Horário Selecionado: {{ \Carbon\Carbon::parse($selected_date)->format('d/m/Y') }} às {{ $selected_time }}
                        </div>
                        <p>Paciente: <strong>{{ $patient_name }}</strong> • Procedimento: <strong>{{ $triage_suggested_procedure }}</strong></p>
                    </div>
                @endif

                <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                    <button type="button" wire:click="previousStep" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                        &larr; Voltar
                    </button>

                    <button type="button"
                            wire:click="confirmBooking"
                            @if (! $selected_time) disabled @endif
                            wire:loading.attr="disabled"
                            class="px-7 py-3.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-sm rounded-xl transition shadow-lg flex items-center gap-2">
                        <span wire:loading.remove>Confirmar Agendamento & WhatsApp &rarr;</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Confirmando vaga...
                        </span>
                    </button>
                </div>
            </div>
        @endif

        <!-- ================= STEP 5: SUCESSO & WHATSAPP ================= -->
        @if ($currentStep === 5)
            <div class="text-center py-4 space-y-6">
                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-2xl font-black text-slate-900">Pré-Agendamento Concluído!</h3>
                    <p class="text-sm text-slate-600 mt-1 max-w-md mx-auto">
                        Seu horário foi reservado com sucesso no sistema da <strong>{{ $clinic->name }}</strong>.
                    </p>
                </div>

                <!-- Detalhes do Agendamento -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 max-w-md mx-auto text-left space-y-3 text-sm">
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-slate-500">Paciente:</span>
                        <strong class="text-slate-900">{{ $patient_name }}</strong>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-slate-500">Data & Horário:</span>
                        <strong class="text-emerald-700">{{ $confirmed_datetime_formatted }}</strong>
                    </div>
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-slate-500">Procedimento:</span>
                        <strong class="text-slate-900">{{ $triage_suggested_procedure }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Prioridade:</span>
                        <span class="font-bold capitalize text-slate-900">{{ $triage_urgency }}</span>
                    </div>
                </div>

                <!-- Call to Action: WhatsApp -->
                @if ($whatsapp_url)
                    <div class="pt-2 max-w-md mx-auto space-y-3">
                        <a href="{{ $whatsapp_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center gap-3 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-base rounded-2xl transition shadow-lg transform hover:-translate-y-0.5">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.966.54 1.761.88 2.796.88 3.183 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.077-2.257-.565-1.921-.791-3.158-2.73-3.254-2.859-.096-.129-.773-1.028-.773-1.96 0-.933.488-1.393.662-1.583.174-.19.38-.238.507-.238.127 0 .254.001.365.006.118.005.277-.045.433.332.162.392.553 1.349.602 1.448.049.099.082.215.016.345-.065.131-.098.213-.196.327-.098.115-.206.257-.294.345-.1.1-.205.209-.089.408.116.199.516.852 1.107 1.378.761.678 1.403.888 1.602.987.199.099.316.083.433-.051.117-.134.502-.584.636-.784.134-.2.268-.167.45-.1.182.067 1.156.545 1.354.644.198.099.33.149.379.233.049.084.049.489-.095.894z"/>
                            </svg>
                            <span>Abrir WhatsApp para Confirmar</span>
                        </a>
                        <p class="text-xs text-slate-500">
                            Clique no botão acima para enviar a mensagem pré-formatada para a recepção da clínica e finalizar sua confirmação!
                        </p>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>

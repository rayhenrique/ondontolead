<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Cadastrar Nova Clínica') }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Cadastre uma nova clínica com provisionamento inicial e 14 dias de teste grátis.</p>
            </div>
            <a href="{{ route('admin.clinics.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
                <span>&larr;</span>
                <span>Voltar para Clínicas</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5">

            <form method="POST" action="{{ route('admin.clinics.store') }}" class="space-y-8">
                @csrf

                <!-- Dados da Clínica -->
                <div>
                    <h2 class="font-heading font-bold text-base text-slate-900 border-b border-slate-100 pb-3 mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Dados da Clínica (Tenant)</span>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nome da Clínica *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="Ex: Clínica Sorriso Perfeito" />
                            @error('name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Slug Público (URL) *</label>
                            <div class="flex rounded-xl overflow-hidden border border-slate-200 bg-slate-50/70 focus-within:bg-white focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition">
                                <span class="inline-flex items-center px-3 text-slate-400 font-mono text-xs border-r border-slate-200">/</span>
                                <input type="text" name="slug" value="{{ old('slug') }}" required class="flex-1 min-w-0 block w-full text-xs px-3.5 py-3 text-slate-900 bg-transparent border-0 outline-none" placeholder="sorriso-perfeito" />
                            </div>
                            @error('slug') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">WhatsApp para Transbordo *</label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="Ex: +5511999999999" />
                            <p class="text-[11px] text-slate-400">Número com DDI e DDD que receberá as mensagens dos pacientes.</p>
                            @error('whatsapp_number') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Plano Inicial *</label>
                            <select name="plan_id" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none">
                                <option value="">Selecione um plano...</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}" @selected(old('plan_id') == $plan->id)>
                                        {{ $plan->name }} (R$ {{ number_format((float) $plan->price, 2, ',', '.') }}/mês)
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status da Assinatura *</label>
                            <select name="subscription_status" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none">
                                <option value="trial" @selected(old('subscription_status', 'trial') === 'trial')>Em Período de Testes (Trial)</option>
                                <option value="active" @selected(old('subscription_status') === 'active')>Ativa</option>
                                <option value="past_due" @selected(old('subscription_status') === 'past_due')>Atrasada / Bloqueada</option>
                                <option value="canceled" @selected(old('subscription_status') === 'canceled')>Cancelada</option>
                            </select>
                            @error('subscription_status') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Duração do Trial (dias)</label>
                            <input type="number" name="trial_days" value="{{ old('trial_days', 14) }}" min="0" max="365" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                            <p class="text-[11px] text-slate-400">Padrão: 14 dias de teste grátis (sem necessidade de cartão de crédito).</p>
                            @error('trial_days') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Dados do Usuário Gestor Inicial -->
                <div>
                    <h2 class="font-heading font-bold text-base text-slate-900 border-b border-slate-100 pb-3 mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        <span>Usuário Gestor da Clínica</span>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nome do Gestor *</label>
                            <input type="text" name="user_name" value="{{ old('user_name') }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="Ex: Dr. Roberto Silva" />
                            @error('user_name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">E-mail de Login *</label>
                            <input type="email" name="user_email" value="{{ old('user_email') }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="roberto@clinica.com" />
                            @error('user_email') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Senha Provisória *</label>
                            <input type="password" name="user_password" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="Mínimo 8 caracteres" />
                            @error('user_password') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.clinics.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/20 transition cursor-pointer">
                        Cadastrar Clínica
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-admin-layout>

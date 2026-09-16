<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    Editar Clínica: {{ $clinic->name }}
                </h1>
                <p class="text-xs text-slate-400 mt-1">Cadastrada em {{ $clinic->created_at?->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="POST" action="{{ route('admin.clinics.impersonate', $clinic) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-sky-50 border border-sky-200/80 rounded-xl text-xs font-bold text-sky-700 hover:bg-sky-100 transition shadow-2xs cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>Personificar Clínica</span>
                    </button>
                </form>
                <a href="{{ route('admin.clinics.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
                    <span>&larr;</span>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5">

            <form method="POST" action="{{ route('admin.clinics.update', $clinic) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nome da Clínica *</label>
                        <input type="text" name="name" value="{{ old('name', $clinic->name) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Slug Público (URL) *</label>
                        <div class="flex rounded-xl overflow-hidden border border-slate-200 bg-slate-50/70 focus-within:bg-white focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20 transition">
                            <span class="inline-flex items-center px-3 text-slate-400 font-mono text-xs border-r border-slate-200">/</span>
                            <input type="text" name="slug" value="{{ old('slug', $clinic->slug) }}" required class="flex-1 min-w-0 block w-full text-xs px-3.5 py-3 text-slate-900 bg-transparent border-0 outline-none" />
                        </div>
                        @error('slug') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">WhatsApp para Transbordo *</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $clinic->whatsapp_number) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('whatsapp_number') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Plano da Clínica *</label>
                        <select name="plan_id" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none">
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" @selected(old('plan_id', $clinic->plan_id) == $plan->id)>
                                    {{ $plan->name }} (R$ {{ number_format((float) $plan->price, 2, ',', '.') }}/mês)
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Situação da Assinatura *</label>
                        <select name="subscription_status" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none">
                            <option value="trial" @selected(old('subscription_status', $clinic->subscription_status) === 'trial')>Em Período de Testes (Trial)</option>
                            <option value="active" @selected(old('subscription_status', $clinic->subscription_status) === 'active')>Ativa</option>
                            <option value="past_due" @selected(old('subscription_status', $clinic->subscription_status) === 'past_due')>Atrasada / Inadimplente</option>
                            <option value="canceled" @selected(old('subscription_status', $clinic->subscription_status) === 'canceled')>Cancelada</option>
                        </select>
                        @error('subscription_status') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Data de Término do Trial</label>
                        <input type="date" name="trial_ends_at" value="{{ old('trial_ends_at', $clinic->trial_ends_at?->format('Y-m-d')) }}" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('trial_ends_at') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 bg-sky-50/70 p-5 rounded-2xl border border-sky-100 space-y-2">
                        <label class="block text-xs font-bold text-sky-900 uppercase tracking-wider">Prorrogar Trial (dias adicionais)</label>
                        <div class="flex items-center space-x-3">
                            <input type="number" name="extend_trial_days" min="1" max="365" placeholder="Ex: 7" class="w-32 text-xs bg-white border border-sky-200 rounded-xl px-4 py-2.5 text-slate-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition outline-none" />
                            <span class="text-xs text-sky-700">Informe a quantidade de dias para estender a partir da validade atual ou de hoje.</span>
                        </div>
                        @error('extend_trial_days') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.clinics.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/20 transition cursor-pointer">
                        Salvar Alterações
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-admin-layout>

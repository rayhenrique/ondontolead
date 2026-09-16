<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    Editar Plano: {{ $plan->name }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Ajuste os parâmetros contratuais e técnicos do plano.</p>
            </div>
            <a href="{{ route('admin.plans.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
                <span>&larr;</span>
                <span>Voltar para Planos</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5">

            <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nome do Plano *</label>
                        <input type="text" name="name" value="{{ old('name', $plan->name) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Slug Único *</label>
                        <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('slug') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Preço Mensal (R$) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('price') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Limite de Agendamentos / Mês *</label>
                        <input type="number" name="max_appointments_per_month" value="{{ old('max_appointments_per_month', $plan->max_appointments_per_month) }}" min="1" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('max_appointments_per_month') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">ID do Plano no Mercado Pago</label>
                        <input type="text" name="mp_plan_id" value="{{ old('mp_plan_id', $plan->mp_plan_id) }}" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none font-mono" />
                        @error('mp_plan_id') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 flex items-center pt-2">
                        <input type="hidden" name="is_active" value="0" />
                        <label for="is_active" class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $plan->is_active)) class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition" />
                            <span class="text-xs font-semibold text-slate-700">Plano ativo (disponível para novas clínicas)</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.plans.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/20 transition cursor-pointer">
                        Atualizar Plano
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-admin-layout>

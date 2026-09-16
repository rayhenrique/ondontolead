<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Gestão de Planos de Assinatura') }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Configure planos, preços, limites mensais de agendamento e identificadores do Mercado Pago.</p>
            </div>
            <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Novo Plano</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium rounded-2xl flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->has('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium rounded-2xl flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $errors->first('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/80 text-slate-400 uppercase text-[10px] font-mono tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 text-left font-bold">Nome / Slug</th>
                            <th class="px-6 py-3.5 text-left font-bold">Preço Mensal</th>
                            <th class="px-6 py-3.5 text-left font-bold">Limite Agendamentos</th>
                            <th class="px-6 py-3.5 text-left font-bold">ID Mercado Pago</th>
                            <th class="px-6 py-3.5 text-left font-bold">Clínicas Ativas</th>
                            <th class="px-6 py-3.5 text-left font-bold">Situação</th>
                            <th class="px-6 py-3.5 text-right font-bold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($plans as $plan)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-sm">{{ $plan->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $plan->slug }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900 font-heading text-sm">
                                    R$ {{ number_format((float) $plan->price, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-medium">
                                    {{ $plan->max_appointments_per_month }} / mês
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[11px] text-slate-400 font-mono">
                                    {{ $plan->mp_plan_id ?: '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-700">
                                    {{ $plan->clinics_count }} clínica(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($plan->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                    <a href="{{ route('admin.plans.edit', $plan) }}" class="text-[11px] font-bold text-sky-700 hover:text-sky-800 bg-sky-50 hover:bg-sky-100 px-2.5 py-1.5 rounded-lg border border-sky-200/60 transition">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este plano?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg border border-rose-200/60 transition cursor-pointer">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                    Nenhum plano cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>

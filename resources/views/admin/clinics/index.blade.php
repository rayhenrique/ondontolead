<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Gestão de Clínicas (Tenants)') }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Gerencie planos, status de assinatura, prazos de trial e personificação de acesso.</p>
            </div>
            <a href="{{ route('admin.clinics.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Nova Clínica</span>
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

        <!-- Filtros e Busca -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5">
            <form method="GET" action="{{ route('admin.clinics.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Buscar</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nome, slug ou WhatsApp..." class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status da Assinatura</label>
                    <select name="status" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none">
                        <option value="">Todos os status</option>
                        <option value="active" @selected($status === 'active')>Ativa</option>
                        <option value="trial" @selected($status === 'trial')>Em Trial (14 dias)</option>
                        <option value="past_due" @selected($status === 'past_due')>Atrasada</option>
                        <option value="canceled" @selected($status === 'canceled')>Cancelada</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Plano</label>
                    <select name="plan_id" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none">
                        <option value="">Todos os planos</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" @selected($planId === $plan->id)>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-slate-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl hover:bg-slate-800 transition shadow-xs cursor-pointer">
                        Filtrar
                    </button>
                    @if ($search || $status || $planId)
                        <a href="{{ route('admin.clinics.index') }}" class="bg-slate-100 text-slate-600 text-xs font-bold py-2.5 px-3 rounded-xl hover:bg-slate-200 transition text-center">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabela de Clínicas -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/80 text-slate-400 uppercase text-[10px] font-mono tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 text-left font-bold">Clínica / Slug</th>
                            <th class="px-6 py-3.5 text-left font-bold">Plano & Valor</th>
                            <th class="px-6 py-3.5 text-left font-bold">Status / Validade</th>
                            <th class="px-6 py-3.5 text-left font-bold">Uso</th>
                            <th class="px-6 py-3.5 text-right font-bold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($clinics as $clinic)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-sm">{{ $clinic->name }}</div>
                                    <div class="flex items-center space-x-2 text-xs text-slate-400 mt-0.5">
                                        <span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-700 font-mono text-[10px] border border-slate-200/60">/{{ $clinic->slug }}</span>
                                        <span>•</span>
                                        <span>{{ $clinic->whatsapp_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $clinic->plan?->name ?? 'Sem plano' }}</div>
                                    <div class="text-[11px] text-slate-400">R$ {{ number_format((float) ($clinic->plan?->price ?? 0), 2, ',', '.') }}/mês</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($clinic->subscription_status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Ativa
                                        </span>
                                    @elseif ($clinic->subscription_status === 'trial')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                            Trial
                                        </span>
                                        @if ($clinic->trial_ends_at)
                                            <div class="text-[10px] text-slate-400 mt-0.5">
                                                Até {{ $clinic->trial_ends_at->format('d/m/Y') }}
                                                @if ($clinic->trial_ends_at->isPast())
                                                    <span class="text-rose-600 font-bold">(Expirado)</span>
                                                @endif
                                            </div>
                                        @endif
                                    @elseif ($clinic->subscription_status === 'past_due')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Atrasada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Cancelada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    <div><strong>{{ $clinic->appointments_count }}</strong> agendamento(s)</div>
                                    <div class="text-slate-400"><strong>{{ $clinic->users_count }}</strong> usuário(s)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                    <!-- Impersonate Button -->
                                    <form method="POST" action="{{ route('admin.clinics.impersonate', $clinic) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-bold text-sky-700 hover:text-sky-800 bg-sky-50 hover:bg-sky-100 px-2.5 py-1.5 rounded-lg border border-sky-200/60 transition cursor-pointer" title="Personificar (entrar no painel desta clínica)">
                                            Personificar
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.clinics.edit', $clinic) }}" class="text-[11px] font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-lg transition">
                                        Editar
                                    </a>

                                    <!-- Quick Status Toggle -->
                                    <form method="POST" action="{{ route('admin.clinics.status', $clinic) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if ($clinic->subscription_status === 'active')
                                            <input type="hidden" name="subscription_status" value="past_due" />
                                            <button type="submit" class="text-[11px] font-bold text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg border border-amber-200/60 transition cursor-pointer" title="Bloquear acesso temporariamente">
                                                Bloquear
                                            </button>
                                        @else
                                            <input type="hidden" name="subscription_status" value="active" />
                                            <button type="submit" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-lg border border-emerald-200/60 transition cursor-pointer" title="Ativar acesso">
                                                Ativar
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                    Nenhuma clínica encontrada com os critérios informados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($clinics->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $clinics->links() }}
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>

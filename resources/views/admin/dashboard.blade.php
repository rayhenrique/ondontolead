<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Painel Global do SuperAdmin') }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Visão unificada de faturamento projetado, saúde dos tenants e fluxo de agendamentos.
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.clinics.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Nova Clínica</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium rounded-2xl flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- MRR Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 flex items-center justify-between relative overflow-hidden group hover:shadow-md transition">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono block">MRR Projetado</span>
                    <div class="font-heading text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        R$ {{ number_format($metrics['mrr'], 2, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1.5 pt-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Assinaturas ativas</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:scale-105 transition transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Total Clínicas -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 flex items-center justify-between relative overflow-hidden group hover:shadow-md transition">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono block">Total de Clínicas</span>
                    <div class="font-heading text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $metrics['total_clinics'] }}
                    </div>
                    <div class="flex items-center space-x-2 text-[11px] text-slate-500 pt-1 font-medium">
                        <span class="text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-100">{{ $metrics['active_clinics'] }} ativas</span>
                        <span>•</span>
                        <span class="text-sky-700 font-bold bg-sky-50 px-1.5 py-0.2 rounded border border-sky-100">{{ $metrics['trial_clinics'] }} trial</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100 group-hover:scale-105 transition transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>

            <!-- Inadimplentes / Alerta -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 flex items-center justify-between relative overflow-hidden group hover:shadow-md transition">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono block">Inadimplentes / Canceladas</span>
                    <div class="font-heading text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $metrics['delinquent_clinics'] + $metrics['canceled_clinics'] }}
                    </div>
                    <div class="flex items-center space-x-2 text-[11px] text-slate-500 pt-1 font-medium">
                        <span class="text-amber-700 font-semibold">{{ $metrics['delinquent_clinics'] }} atrasadas</span>
                        <span>•</span>
                        <span class="text-rose-700 font-semibold">{{ $metrics['canceled_clinics'] }} canceladas</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100 group-hover:scale-105 transition transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>

            <!-- Volume de Agendamentos -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 flex items-center justify-between relative overflow-hidden group hover:shadow-md transition">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider font-mono block">Agendamentos</span>
                    <div class="font-heading text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $metrics['total_appointments'] }}
                    </div>
                    <p class="text-[11px] text-slate-500 pt-1 font-medium">
                        <strong class="text-sky-700 font-bold">{{ $metrics['month_appointments'] }}</strong> consultas este mês
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100 group-hover:scale-105 transition transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>

        <!-- Recent Clinics Table Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-heading font-bold text-base text-slate-900">Últimas Clínicas Cadastradas</h2>
                    <p class="text-xs text-slate-400">Tenants integrados mais recentemente no ecossistema.</p>
                </div>
                <a href="{{ route('admin.clinics.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 transition">
                    <span>Ver todas as clínicas</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/80 text-slate-400 uppercase text-[10px] font-mono tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 text-left font-bold">Clínica</th>
                            <th class="px-6 py-3.5 text-left font-bold">Slug / WhatsApp</th>
                            <th class="px-6 py-3.5 text-left font-bold">Plano</th>
                            <th class="px-6 py-3.5 text-left font-bold">Status</th>
                            <th class="px-6 py-3.5 text-left font-bold">Cadastro</th>
                            <th class="px-6 py-3.5 text-right font-bold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($metrics['recent_clinics'] as $clinic)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-sm">{{ $clinic->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    <div><span class="text-[11px] font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 border border-slate-200/60">/{{ $clinic->slug }}</span></div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $clinic->whatsapp_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">
                                    {{ $clinic->plan?->name ?? 'Sem plano' }}
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
                                            Trial (14 dias)
                                        </span>
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
                                <td class="px-6 py-4 whitespace-nowrap text-slate-400 font-mono text-[11px]">
                                    {{ $clinic->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <form method="POST" action="{{ route('admin.clinics.impersonate', $clinic) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-bold text-sky-700 hover:text-sky-800 bg-sky-50 hover:bg-sky-100 px-2.5 py-1 rounded-lg border border-sky-200/60 transition cursor-pointer" title="Acessar painel como este tenant">
                                            Personificar
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.clinics.edit', $clinic) }}" class="text-[11px] font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200/80 px-2.5 py-1 rounded-lg transition">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                    Nenhuma clínica cadastrada ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Navigation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <a href="{{ route('admin.plans.index') }}" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:border-sky-300 hover:shadow-md transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-slate-900 group-hover:text-sky-600 transition">Gestão de Planos</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Criar e ajustar planos, limites e precificação</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.releases.index') }}" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:border-emerald-300 hover:shadow-md transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-slate-900 group-hover:text-emerald-600 transition">Changelog & Releases</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Publicar novidades que disparam modais para usuários</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.settings.edit') }}" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-md transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-slate-900 group-hover:text-slate-900 transition">Configurações Globais</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Identidade, logo, rodapé e cache do sistema</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</x-admin-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Painel Global do SuperAdmin') }}
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.clinics.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nova Clínica
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- MRR Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">MRR Projetado</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">R$ {{ number_format($metrics['mrr'], 2, ',', '.') }}</h3>
                        <p class="text-xs text-emerald-600 mt-1 font-medium flex items-center">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 me-1"></span> Assinaturas ativas
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <!-- Total Clínicas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total de Clínicas</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total_clinics'] }}</h3>
                        <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                            <span class="text-emerald-600 font-semibold">{{ $metrics['active_clinics'] }} ativas</span>
                            <span>•</span>
                            <span class="text-indigo-600 font-semibold">{{ $metrics['trial_clinics'] }} trial</span>
                        </div>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>

                <!-- Inadimplentes / Alerta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Inadimplentes / Canceladas</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['delinquent_clinics'] + $metrics['canceled_clinics'] }}</h3>
                        <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                            <span class="text-amber-600 font-semibold">{{ $metrics['delinquent_clinics'] }} atrasadas</span>
                            <span>•</span>
                            <span class="text-rose-600 font-semibold">{{ $metrics['canceled_clinics'] }} canceladas</span>
                        </div>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>

                <!-- Volume de Agendamentos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Agendamentos</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['total_appointments'] }}</h3>
                        <p class="text-xs text-gray-500 mt-1 font-medium">
                            <strong class="text-indigo-600">{{ $metrics['month_appointments'] }}</strong> agendados este mês
                        </p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Recent Clinics Table -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-800">Últimas Clínicas Cadastradas</h3>
                    <a href="{{ route('admin.clinics.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Ver todas as clínicas &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium">Clínica</th>
                                <th class="px-6 py-3 text-left font-medium">Slug / WhatsApp</th>
                                <th class="px-6 py-3 text-left font-medium">Plano</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium">Cadastro</th>
                                <th class="px-6 py-3 text-right font-medium">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($metrics['recent_clinics'] as $clinic)
                                <tr class="hover:bg-gray-50/75 transition">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $clinic->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        <div><span class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700">/{{ $clinic->slug }}</span></div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $clinic->whatsapp_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $clinic->plan?->name ?? 'Sem plano' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($clinic->subscription_status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Ativa</span>
                                        @elseif ($clinic->subscription_status === 'trial')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">Trial</span>
                                        @elseif ($clinic->subscription_status === 'past_due')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Atrasada</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        {{ $clinic->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                        <form method="POST" action="{{ route('admin.clinics.impersonate', $clinic) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded transition" title="Acessar painel como este tenant">
                                                Impersonar
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.clinics.edit', $clinic) }}" class="text-xs font-medium text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded transition">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                        Nenhuma clínica cadastrada ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Navigation Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.plans.index') }}" class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:border-indigo-300 hover:shadow transition group">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Gestão de Planos</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Criar e ajustar planos, limites e precificação</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.releases.index') }}" class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:border-indigo-300 hover:shadow transition group">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Changelog & Releases</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Publicar novidades que disparam modais para usuários</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.settings.edit') }}" class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:border-indigo-300 hover:shadow transition group">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-gray-50 text-gray-600 rounded-lg group-hover:bg-gray-800 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Configurações Globais</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Identidade, logo, rodapé e cache do sistema</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

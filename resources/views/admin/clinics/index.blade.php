<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Gestão de Clínicas (Tenants)') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Gerencie planos, status de assinatura, prazos de trial e personificação de acesso.</p>
            </div>
            <a href="{{ route('admin.clinics.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nova Clínica
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Filtros e Busca -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.clinics.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nome, slug ou WhatsApp..." class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status da Assinatura</label>
                        <select name="status" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos os status</option>
                            <option value="active" @selected($status === 'active')>Ativa</option>
                            <option value="trial" @selected($status === 'trial')>Em Trial</option>
                            <option value="past_due" @selected($status === 'past_due')>Atrasada</option>
                            <option value="canceled" @selected($status === 'canceled')>Cancelada</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Plano</label>
                        <select name="plan_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos os planos</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" @selected($planId === $plan->id)>{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full bg-gray-800 text-white text-sm font-medium py-2 px-4 rounded-md hover:bg-gray-700 transition">
                            Filtrar
                        </button>
                        @if ($search || $status || $planId)
                            <a href="{{ route('admin.clinics.index') }}" class="bg-gray-100 text-gray-600 text-sm font-medium py-2 px-4 rounded-md hover:bg-gray-200 transition text-center">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela de Clínicas -->
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium">Clínica / Slug</th>
                                <th class="px-6 py-3 text-left font-medium">Plano & Valor</th>
                                <th class="px-6 py-3 text-left font-medium">Status / Validade</th>
                                <th class="px-6 py-3 text-left font-medium">Uso</th>
                                <th class="px-6 py-3 text-right font-medium">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($clinics as $clinic)
                                <tr class="hover:bg-gray-50/75 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900">{{ $clinic->name }}</div>
                                        <div class="flex items-center space-x-2 text-xs text-gray-500 mt-0.5">
                                            <span class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 font-mono">/{{ $clinic->slug }}</span>
                                            <span>•</span>
                                            <span>{{ $clinic->whatsapp_number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-800">{{ $clinic->plan?->name ?? 'Sem plano' }}</div>
                                        <div class="text-xs text-gray-500">R$ {{ number_format((float) ($clinic->plan?->price ?? 0), 2, ',', '.') }}/mês</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($clinic->subscription_status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Ativa</span>
                                        @elseif ($clinic->subscription_status === 'trial')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">Trial</span>
                                            @if ($clinic->trial_ends_at)
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    Até {{ $clinic->trial_ends_at->format('d/m/Y') }}
                                                    @if ($clinic->trial_ends_at->isPast())
                                                        <span class="text-rose-600 font-bold">(Expirado)</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @elseif ($clinic->subscription_status === 'past_due')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Atrasada</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        <div><strong>{{ $clinic->appointments_count }}</strong> agendamento(s)</div>
                                        <div><strong>{{ $clinic->users_count }}</strong> usuário(s)</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                        <!-- Impersonate Button -->
                                        <form method="POST" action="{{ route('admin.clinics.impersonate', $clinic) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded transition" title="Personificar (entrar no painel desta clínica)">
                                                Personificar
                                            </button>
                                        </form>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.clinics.edit', $clinic) }}" class="text-xs font-medium text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-2.5 py-1.5 rounded transition">
                                            Editar
                                        </a>

                                        <!-- Quick Status Toggle -->
                                        <form method="POST" action="{{ route('admin.clinics.status', $clinic) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if ($clinic->subscription_status === 'active')
                                                <input type="hidden" name="subscription_status" value="past_due" />
                                                <button type="submit" class="text-xs font-medium text-amber-700 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-2 py-1.5 rounded transition" title="Bloquear acesso temporariamente">
                                                    Bloquear
                                                </button>
                                            @else
                                                <input type="hidden" name="subscription_status" value="active" />
                                                <button type="submit" class="text-xs font-medium text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-2 py-1.5 rounded transition" title="Ativar acesso">
                                                    Ativar
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                        Nenhuma clínica encontrada com os critérios informados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($clinics->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $clinics->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

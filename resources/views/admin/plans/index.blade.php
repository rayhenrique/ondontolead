<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Gestão de Planos de Assinatura') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Configure planos, preços, limites mensais de agendamento e identificadores do Mercado Pago.</p>
            </div>
            <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Novo Plano
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

            @if ($errors->has('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded shadow-sm">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium">Nome / Slug</th>
                                <th class="px-6 py-3 text-left font-medium">Preço Mensal</th>
                                <th class="px-6 py-3 text-left font-medium">Limite Agendamentos</th>
                                <th class="px-6 py-3 text-left font-medium">ID Mercado Pago</th>
                                <th class="px-6 py-3 text-left font-medium">Clínicas Ativas</th>
                                <th class="px-6 py-3 text-left font-medium">Situação</th>
                                <th class="px-6 py-3 text-right font-medium">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($plans as $plan)
                                <tr class="hover:bg-gray-50/75 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900">{{ $plan->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $plan->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        R$ {{ number_format((float) $plan->price, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $plan->max_appointments_per_month }} / mês
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">
                                        {{ $plan->mp_plan_id ?: '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-700">
                                        {{ $plan->clinics_count }} clínica(s)
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($plan->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Ativo</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded transition">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este plano?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded transition">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                        Nenhum plano cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

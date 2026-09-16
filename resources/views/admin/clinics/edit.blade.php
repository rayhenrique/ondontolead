<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editar Clínica: {{ $clinic->name }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Cadastrada em {{ $clinic->created_at?->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="POST" action="{{ route('admin.clinics.impersonate', $clinic) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 border border-indigo-200 rounded-md text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                        Personificar Clínica
                    </button>
                </form>
                <a href="{{ route('admin.clinics.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    &larr; Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6 md:p-8">

                <form method="POST" action="{{ route('admin.clinics.update', $clinic) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Clínica *</label>
                            <input type="text" name="name" value="{{ old('name', $clinic->name) }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug Público (URL) *</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-xs">/</span>
                                <input type="text" name="slug" value="{{ old('slug', $clinic->slug) }}" required class="flex-1 min-w-0 block w-full text-sm rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            @error('slug') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp para Transbordo *</label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $clinic->whatsapp_number) }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('whatsapp_number') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plano da Clínica *</label>
                            <select name="plan_id" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}" @selected(old('plan_id', $clinic->plan_id) == $plan->id)>
                                        {{ $plan->name }} (R$ {{ number_format((float) $plan->price, 2, ',', '.') }}/mês)
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Situação da Assinatura *</label>
                            <select name="subscription_status" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="trial" @selected(old('subscription_status', $clinic->subscription_status) === 'trial')>Em Período de Testes (Trial)</option>
                                <option value="active" @selected(old('subscription_status', $clinic->subscription_status) === 'active')>Ativa</option>
                                <option value="past_due" @selected(old('subscription_status', $clinic->subscription_status) === 'past_due')>Atrasada / Inadimplente</option>
                                <option value="canceled" @selected(old('subscription_status', $clinic->subscription_status) === 'canceled')>Cancelada</option>
                            </select>
                            @error('subscription_status') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Término do Trial</label>
                            <input type="date" name="trial_ends_at" value="{{ old('trial_ends_at', $clinic->trial_ends_at?->format('Y-m-d')) }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('trial_ends_at') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 bg-indigo-50/50 p-4 rounded-lg border border-indigo-100">
                            <label class="block text-sm font-medium text-indigo-900 mb-1">Prorrogar Trial (dias adicionais)</label>
                            <div class="flex items-center space-x-3">
                                <input type="number" name="extend_trial_days" min="1" max="365" placeholder="Ex: 7" class="w-32 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <span class="text-xs text-indigo-700">Informe a quantidade de dias para estender a partir da validade atual ou de hoje.</span>
                            </div>
                            @error('extend_trial_days') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.clinics.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-semibold text-white hover:bg-indigo-700 transition">
                            Salvar Alterações
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>

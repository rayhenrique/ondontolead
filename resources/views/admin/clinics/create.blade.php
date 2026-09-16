<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cadastrar Nova Clínica') }}
            </h2>
            <a href="{{ route('admin.clinics.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                &larr; Voltar para Clínicas
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6 md:p-8">

                <form method="POST" action="{{ route('admin.clinics.store') }}" class="space-y-6">
                    @csrf

                    <!-- Dados da Clínica -->
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                            Dados da Clínica (Tenant)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Clínica *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: Clínica Sorriso Perfeito" />
                                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Slug Público (URL) *</label>
                                <div class="flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-xs">/</span>
                                    <input type="text" name="slug" value="{{ old('slug') }}" required class="flex-1 min-w-0 block w-full text-sm rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="sorriso-perfeito" />
                                </div>
                                @error('slug') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp para Transbordo *</label>
                                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: +5511999999999" />
                                <p class="text-xs text-gray-500 mt-1">Número com DDI e DDD que receberá as mensagens dos pacientes.</p>
                                @error('whatsapp_number') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plano Inicial *</label>
                                <select name="plan_id" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione um plano...</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" @selected(old('plan_id') == $plan->id)>
                                            {{ $plan->name }} (R$ {{ number_format((float) $plan->price, 2, ',', '.') }}/mês)
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status da Assinatura *</label>
                                <select name="subscription_status" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="trial" @selected(old('subscription_status', 'trial') === 'trial')>Em Período de Testes (Trial)</option>
                                    <option value="active" @selected(old('subscription_status') === 'active')>Ativa</option>
                                    <option value="past_due" @selected(old('subscription_status') === 'past_due')>Atrasada / Bloqueada</option>
                                    <option value="canceled" @selected(old('subscription_status') === 'canceled')>Cancelada</option>
                                </select>
                                @error('subscription_status') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Duração do Trial (dias)</label>
                                <input type="number" name="trial_days" value="{{ old('trial_days', 14) }}" min="0" max="365" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                <p class="text-xs text-gray-500 mt-1">Padrão: 14 dias de teste grátis (sem necessidade de cartão de crédito).</p>
                                @error('trial_days') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dados do Usuário Gestor Inicial -->
                    <div class="pt-4">
                        <h3 class="text-base font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                            Usuário Gestor da Clínica
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Gestor *</label>
                                <input type="text" name="user_name" value="{{ old('user_name') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: Dr. Roberto Silva" />
                                @error('user_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail de Login *</label>
                                <input type="email" name="user_email" value="{{ old('user_email') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="roberto@clinica.com" />
                                @error('user_email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Senha Provisória *</label>
                                <input type="password" name="user_password" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Mínimo 8 caracteres" />
                                @error('user_password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.clinics.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-semibold text-white hover:bg-indigo-700 transition">
                            Cadastrar Clínica
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>

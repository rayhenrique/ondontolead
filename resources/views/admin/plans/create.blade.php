<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Criar Novo Plano') }}
            </h2>
            <a href="{{ route('admin.plans.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                &larr; Voltar para Planos
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6 md:p-8">

                <form method="POST" action="{{ route('admin.plans.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Plano *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: Profissional" />
                            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug Único *</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="profissional" />
                            @error('slug') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preço Mensal (R$) *</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', '149.90') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Limite de Agendamentos / Mês *</label>
                            <input type="number" name="max_appointments_per_month" value="{{ old('max_appointments_per_month', 100) }}" min="1" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('max_appointments_per_month') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ID do Plano no Mercado Pago</label>
                            <input type="text" name="mp_plan_id" value="{{ old('mp_plan_id') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono" placeholder="Ex: 2c9380848f..." />
                            <p class="text-xs text-gray-500 mt-1">Identificador de assinatura preapproval_plan do Mercado Pago (opcional no MVP).</p>
                            @error('mp_plan_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 flex items-center">
                            <input type="hidden" name="is_active" value="0" />
                            <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                            <label for="is_active" class="ms-2 text-sm text-gray-700">Plano ativo (disponível para novas clínicas)</label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.plans.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-semibold text-white hover:bg-indigo-700 transition">
                            Salvar Plano
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>

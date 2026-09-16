<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Configurações Globais do Sistema') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Defina a identidade visual, nome do SaaS e textos de rodapé (armazenados com cache persistente).</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                &larr; Voltar para Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-100 p-6 md:p-8">

                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Sistema (Branding) *</label>
                            <input type="text" name="system_name" value="{{ old('system_name', $settings['system_name'] ?? '') }}" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="OdontoLead AI" />
                            <p class="text-xs text-gray-500 mt-1">Exibido na barra de título do navegador, e-mails e rodapés.</p>
                            @error('system_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">URL do Logotipo</label>
                                <input type="text" name="system_logo_url" value="{{ old('system_logo_url', $settings['system_logo_url'] ?? '') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://exemplo.com/logo.png" />
                                <p class="text-xs text-gray-500 mt-1">URL pública do logotipo para o topo do SaaS.</p>
                                @error('system_logo_url') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">URL do Favicon</label>
                                <input type="text" name="system_favicon_url" value="{{ old('system_favicon_url', $settings['system_favicon_url'] ?? '') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://exemplo.com/favicon.ico" />
                                <p class="text-xs text-gray-500 mt-1">Ícone exibido na aba do navegador.</p>
                                @error('system_favicon_url') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Texto de Rodapé (Copyright)</label>
                            <input type="text" name="system_footer_text" value="{{ old('system_footer_text', $settings['system_footer_text'] ?? '') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="© 2026 OdontoLead AI. Todos os direitos reservados." />
                            @error('system_footer_text') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                        <button type="submit" class="px-5 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-semibold text-white hover:bg-indigo-700 transition">
                            Salvar Configurações
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>

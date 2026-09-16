<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Configurações Globais do Sistema') }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Defina a identidade visual, nome do SaaS e textos de rodapé (armazenados com cache persistente).</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
                <span>&larr;</span>
                <span>Voltar para Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium rounded-2xl flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5">

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nome do Sistema (Branding) *</label>
                        <input type="text" name="system_name" value="{{ old('system_name', $settings['system_name'] ?? '') }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="OdontoLead AI" />
                        <p class="text-[11px] text-slate-400">Exibido na barra de título do navegador, e-mails e rodapés.</p>
                        @error('system_name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">URL do Logotipo</label>
                            <input type="text" name="system_logo_url" value="{{ old('system_logo_url', $settings['system_logo_url'] ?? '') }}" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="https://exemplo.com/logo.png" />
                            <p class="text-[11px] text-slate-400">URL pública do logotipo para o topo do SaaS.</p>
                            @error('system_logo_url') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">URL do Favicon</label>
                            <input type="text" name="system_favicon_url" value="{{ old('system_favicon_url', $settings['system_favicon_url'] ?? '') }}" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="https://exemplo.com/favicon.ico" />
                            <p class="text-[11px] text-slate-400">Ícone exibido na aba do navegador.</p>
                            @error('system_favicon_url') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Texto de Rodapé (Copyright)</label>
                        <input type="text" name="system_footer_text" value="{{ old('system_footer_text', $settings['system_footer_text'] ?? '') }}" class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" placeholder="© 2026 OdontoLead AI. Todos os direitos reservados." />
                        @error('system_footer_text') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/20 transition cursor-pointer">
                        Salvar Configurações
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-admin-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading font-black text-2xl text-slate-900 tracking-tight leading-tight flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span>Configurações da Clínica</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Personalize informações públicas, canais de contato e chaves do motor de IA da {{ $clinic->name }}.
                </p>
            </div>
            <div>
                <a href="{{ route('app.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200/80 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition shadow-xs">
                    <span>&larr;</span>
                    <span>Voltar ao Dashboard</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs sm:text-sm font-semibold">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl shadow-xs space-y-1.5">
                    <div class="flex items-center gap-2 font-bold text-sm">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Por favor, corrija os erros abaixo:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs text-rose-700 pl-2 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('app.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Dados Gerais da Clínica -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="font-heading font-black text-lg text-slate-900">Dados da Clínica & Perfil Público</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Defina como sua clínica é identificada pelos pacientes e na URL pública de agendamento online.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-[11px] font-mono font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Nome da Clínica *</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $clinic->name) }}"
                                   required
                                   class="w-full text-xs sm:text-sm border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 shadow-2xs"
                                   placeholder="Ex: Odonto Arte Odontologia">
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="sm:col-span-1" x-data="{ currentSlug: '{{ old('slug', $clinic->slug) }}' }">
                            <label for="slug" class="block text-[11px] font-mono font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Slug / Link Público *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-slate-400 font-mono pointer-events-none">
                                    /
                                </span>
                                <input type="text"
                                       id="slug"
                                       name="slug"
                                       x-model="currentSlug"
                                       required
                                       class="pl-7 w-full font-mono text-xs sm:text-sm border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 shadow-2xs"
                                       placeholder="minha-clinica">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5">
                                Link de agendamento: <span class="font-mono text-sky-700 font-bold" x-text="'{{ url('/') }}/' + currentSlug"></span>
                            </p>
                            @error('slug')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div class="sm:col-span-1">
                            <label for="whatsapp_number" class="block text-[11px] font-mono font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Número do WhatsApp *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.966.54 1.761.88 2.796.88 3.183 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.077-2.257-.565-1.921-.791-3.158-2.73-3.254-2.859-.096-.129-.773-1.028-.773-1.96 0-.933.488-1.393.662-1.583.174-.19.38-.238.507-.238.127 0 .254.001.365.006.118.005.277-.045.433.332.162.392.553 1.349.602 1.448.049.099.082.215.016.345-.065.131-.098.213-.196.327-.098.115-.206.257-.294.345-.1.1-.205.209-.089.408.116.199.516.852 1.107 1.378.761.678 1.403.888 1.602.987.199.099.316.083.433-.051.117-.134.502-.584.636-.784.134-.2.268-.167.45-.1.182.067 1.156.545 1.354.644.198.099.33.149.379.233.049.084.049.489-.095.894z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="whatsapp_number"
                                       name="whatsapp_number"
                                       value="{{ old('whatsapp_number', $clinic->whatsapp_number) }}"
                                       required
                                       class="pl-10 w-full text-xs sm:text-sm border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 shadow-2xs"
                                       placeholder="(11) 99999-9999">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5">Número que receberá notificações e será exibido para seus pacientes.</p>
                            @error('whatsapp_number')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- BYOK - Inteligência Artificial -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-6 sm:p-8 space-y-6"
                     x-data="{ provider: '{{ old('ai_provider', $clinic->ai_provider ?? 'none') }}' }">
                    <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="font-heading font-black text-lg text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span>Inteligência Artificial (BYOK — Bring Your Own Key)</span>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Configure seu próprio motor de IA para triagem semântica automática de sintomas e queixas odontológicas.</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-mono font-bold bg-sky-50 text-sky-700 border border-sky-200/80 w-fit">
                            Criptografia AES-256
                        </span>
                    </div>

                    <div class="space-y-4">
                        <!-- Provider Selection -->
                        <div>
                            <label class="block text-[11px] font-mono font-extrabold uppercase tracking-wider text-slate-600 mb-2.5">Provedor de IA *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Option None -->
                                <label class="border rounded-2xl p-4 flex flex-col cursor-pointer transition shadow-2xs"
                                       :class="provider === 'none' ? 'border-sky-500 bg-sky-50/60 ring-2 ring-sky-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-slate-800">Sem IA Própria</span>
                                        <input type="radio" name="ai_provider" value="none" x-model="provider" class="text-sky-600 focus:ring-sky-500">
                                    </div>
                                    <span class="text-xs text-slate-500 leading-relaxed">Triagem determinística por regras clínicas sem consumo de tokens externos.</span>
                                </label>

                                <!-- Option Gemini -->
                                <label class="border rounded-2xl p-4 flex flex-col cursor-pointer transition shadow-2xs"
                                       :class="provider === 'gemini' ? 'border-sky-500 bg-sky-50/60 ring-2 ring-sky-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-sm font-bold text-slate-800">Google Gemini</span>
                                            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-1.5 py-0.5 rounded">Recomendado</span>
                                        </div>
                                        <input type="radio" name="ai_provider" value="gemini" x-model="provider" class="text-sky-600 focus:ring-sky-500">
                                    </div>
                                    <span class="text-xs text-slate-500 leading-relaxed">Gemini 1.5 Flash. Altíssima velocidade de resposta e custo quase zero.</span>
                                </label>

                                <!-- Option OpenAI -->
                                <label class="border rounded-2xl p-4 flex flex-col cursor-pointer transition shadow-2xs"
                                       :class="provider === 'openai' ? 'border-sky-500 bg-sky-50/60 ring-2 ring-sky-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-slate-800">OpenAI</span>
                                        <input type="radio" name="ai_provider" value="openai" x-model="provider" class="text-sky-600 focus:ring-sky-500">
                                    </div>
                                    <span class="text-xs text-slate-500 leading-relaxed">GPT-4o mini. Processamento semântico avançado e anamnese apurada.</span>
                                </label>
                            </div>
                            @error('ai_provider')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- API Key Input -->
                        <div x-show="provider !== 'none'" x-cloak class="pt-4 border-t border-slate-100">
                            <label for="ai_api_key" class="block text-[11px] font-mono font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">
                                Chave de API Privada (<span x-text="provider === 'gemini' ? 'Google AI Studio Key' : 'OpenAI Secret Key'"></span>)
                            </label>
                            <div class="relative">
                                <input type="password"
                                       id="ai_api_key"
                                       name="ai_api_key"
                                       class="w-full text-xs sm:text-sm font-mono border-slate-200 rounded-xl focus:ring-sky-500 focus:border-sky-500 shadow-2xs"
                                       placeholder="sk-... ou AIza...">
                            </div>

                            @if (! empty($clinic->ai_api_key))
                                <div class="mt-2.5 flex items-center gap-2 text-xs text-emerald-800 bg-emerald-50 px-3.5 py-2 rounded-xl border border-emerald-200">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span>Uma chave de API já está configurada e criptografada com segurança. Deixe em branco para mantê-la inalterada.</span>
                                </div>
                            @else
                                <p class="text-[11px] text-slate-500 mt-1.5">Sua chave é criptografada e nunca exposta no frontend.</p>
                            @endif

                            @error('ai_api_key')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('app.dashboard') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition shadow-xs">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm shadow-emerald-500/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

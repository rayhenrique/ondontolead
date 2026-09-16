<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Configurações da Clínica</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Personalize informações públicas, canal WhatsApp e chaves de Inteligência Artificial da {{ $clinic->name }}.
                </p>
            </div>
            <div>
                <a href="{{ route('app.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-50 transition shadow-sm">
                    &larr; Voltar ao Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-[calc(100vh-140px)]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm space-y-1">
                    <div class="flex items-center gap-2 font-semibold text-sm">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Por favor, corrija os erros abaixo:
                    </div>
                    <ul class="list-disc list-inside text-xs text-rose-700 pl-2">
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
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-lg font-bold text-slate-800">Dados da Clínica & Perfil Público</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Defina como sua clínica é identificada pelos pacientes e na URL de agendamento online.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nome da Clínica *</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $clinic->name) }}"
                                   required
                                   class="w-full text-sm border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                   placeholder="Ex: Odonto Arte Odontologia">
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="sm:col-span-1" x-data="{ currentSlug: '{{ old('slug', $clinic->slug) }}' }">
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Slug / Link Público *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-slate-400 font-mono pointer-events-none">
                                    /
                                </span>
                                <input type="text"
                                       id="slug"
                                       name="slug"
                                       x-model="currentSlug"
                                       required
                                       class="pl-6 w-full font-mono text-sm border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                       placeholder="minha-clinica">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">
                                Link de agendamento: <span class="font-mono text-indigo-600 font-semibold" x-text="'{{ url('/') }}/' + currentSlug"></span>
                            </p>
                            @error('slug')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div class="sm:col-span-1">
                            <label for="whatsapp_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Número do WhatsApp *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4 fill-current text-emerald-600" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.966.54 1.761.88 2.796.88 3.183 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.77-5.768-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.311.045-.698.077-2.257-.565-1.921-.791-3.158-2.73-3.254-2.859-.096-.129-.773-1.028-.773-1.96 0-.933.488-1.393.662-1.583.174-.19.38-.238.507-.238.127 0 .254.001.365.006.118.005.277-.045.433.332.162.392.553 1.349.602 1.448.049.099.082.215.016.345-.065.131-.098.213-.196.327-.098.115-.206.257-.294.345-.1.1-.205.209-.089.408.116.199.516.852 1.107 1.378.761.678 1.403.888 1.602.987.199.099.316.083.433-.051.117-.134.502-.584.636-.784.134-.2.268-.167.45-.1.182.067 1.156.545 1.354.644.198.099.33.149.379.233.049.084.049.489-.095.894z"/>
                                    </svg>
                                </div>
                                <input type="text"
                                       id="whatsapp_number"
                                       name="whatsapp_number"
                                       value="{{ old('whatsapp_number', $clinic->whatsapp_number) }}"
                                       required
                                       class="pl-9 w-full text-sm border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                       placeholder="(11) 99999-9999">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">Número que receberá contatos e será exibido para seus pacientes.</p>
                            @error('whatsapp_number')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- BYOK - Inteligência Artificial -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6"
                     x-data="{ provider: '{{ old('ai_provider', $clinic->ai_provider ?? 'none') }}' }">
                    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Inteligência Artificial (BYOK - Traga sua Chave)
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Configure seu próprio provedor de IA para análise automática de queixas e triagens odontológicas.</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Criptografia AES-256
                        </span>
                    </div>

                    <div class="space-y-4">
                        <!-- Provider Selection -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Provedor de IA *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Option None -->
                                <label class="border rounded-xl p-4 flex flex-col cursor-pointer transition"
                                       :class="provider === 'none' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-slate-800">Sem IA Própria</span>
                                        <input type="radio" name="ai_provider" value="none" x-model="provider" class="text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <span class="text-xs text-slate-500">Usa fluxo de triagem convencional sem consumo de tokens de IA própria.</span>
                                </label>

                                <!-- Option Gemini -->
                                <label class="border rounded-xl p-4 flex flex-col cursor-pointer transition"
                                       :class="provider === 'gemini' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-sm font-bold text-slate-800">Google Gemini</span>
                                            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded">Rápido</span>
                                        </div>
                                        <input type="radio" name="ai_provider" value="gemini" x-model="provider" class="text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <span class="text-xs text-slate-500">Gemini 1.5 Flash. Alta precisão com baixo custo de tokens.</span>
                                </label>

                                <!-- Option OpenAI -->
                                <label class="border rounded-xl p-4 flex flex-col cursor-pointer transition"
                                       :class="provider === 'openai' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-slate-800">OpenAI</span>
                                        <input type="radio" name="ai_provider" value="openai" x-model="provider" class="text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <span class="text-xs text-slate-500">GPT-4o mini. Processamento semântico avançado de anamnese.</span>
                                </label>
                            </div>
                            @error('ai_provider')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- API Key Input (conditionally visible if provider != none) -->
                        <div x-show="provider !== 'none'" x-cloak class="pt-3 border-t border-slate-100">
                            <label for="ai_api_key" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Chave de API (<span x-text="provider === 'gemini' ? 'Google AI Studio Key' : 'OpenAI Secret Key'"></span>)
                            </label>
                            <div class="relative">
                                <input type="password"
                                       id="ai_api_key"
                                       name="ai_api_key"
                                       class="w-full text-sm font-mono border-slate-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                       placeholder="sk-... ou AIza...">
                            </div>

                            @if (! empty($clinic->ai_api_key))
                                <div class="mt-2 flex items-center gap-1.5 text-xs text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span>Uma chave de API já está configurada e criptografada com segurança. Deixe este campo em branco para mantê-la.</span>
                                </div>
                            @else
                                <p class="text-[11px] text-slate-500 mt-1">Sua chave é armazenada de forma criptografada e nunca é compartilhada ou exposta.</p>
                            @endif

                            @error('ai_api_key')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('app.dashboard') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-semibold text-sm rounded-xl hover:bg-slate-50 transition shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Salvar Alterações
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

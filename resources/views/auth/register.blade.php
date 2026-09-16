<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex flex-col items-center justify-center space-y-2">
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-emerald-500 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-2xl text-slate-800 tracking-tight">OdontoLead <span class="text-indigo-600">AI</span></span>
                </a>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-full">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>14 dias grátis • Sem cartão de crédito</span>
                </div>
            </div>
        </x-slot>

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-slate-900">Crie a conta da sua clínica</h1>
            <p class="text-xs text-slate-500 mt-1">Comece hoje mesmo seu teste gratuito com acesso completo a todos os recursos.</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Nome do Gestor -->
            <div>
                <x-label for="name" value="Seu Nome Completo *" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ex: Dra. Ana Paula Silva" />
            </div>

            <!-- Email -->
            <div>
                <x-label for="email" value="Seu E-mail Profissional *" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ana@odontosaude.com.br" />
            </div>

            <!-- Nome da Clínica -->
            <div>
                <x-label for="clinic_name" value="Nome da Clínica Odontológica (opcional)" />
                <x-input id="clinic_name" class="block mt-1 w-full" type="text" name="clinic_name" :value="old('clinic_name')" placeholder="Ex: Clínica Odonto Sorriso" />
                <p class="text-xs text-slate-400 mt-0.5">Se não informado, criaremos com base no seu nome.</p>
            </div>

            <!-- WhatsApp da Clínica -->
            <div>
                <x-label for="whatsapp_number" value="WhatsApp da Recepção (opcional)" />
                <x-input id="whatsapp_number" class="block mt-1 w-full" type="text" name="whatsapp_number" :value="old('whatsapp_number')" placeholder="11999998888" />
                <p class="text-xs text-slate-400 mt-0.5">Para onde serão direcionados os agendamentos qualificados.</p>
            </div>

            <!-- Senha -->
            <div>
                <x-label for="password" value="Senha de Acesso *" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            </div>

            <!-- Confirmação de Senha -->
            <div>
                <x-label for="password_confirmation" value="Confirmar Senha *" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div>
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2 text-xs text-slate-600">
                                {!! __('Eu concordo com os :terms_of_service e com a :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-indigo-600 hover:text-indigo-800">'.__('Termos de Uso').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-indigo-600 hover:text-indigo-800">'.__('Política de Privacidade').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a class="underline text-xs text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    Já tem uma conta? Entrar
                </a>

                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                    Começar Teste de 14 Dias Grátis &rarr;
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Grade de Atendimento e Horários') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Configure os dias em que sua clínica atende e defina bloqueios para feriados ou recessos.</p>
            </div>
            <a href="{{ route('app.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                &larr; Voltar ao Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('clinic.schedule-manager')
        </div>
    </div>
</x-app-layout>

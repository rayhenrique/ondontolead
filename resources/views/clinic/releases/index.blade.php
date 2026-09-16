<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span>Novidades & Atualizações (Changelog)</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Acompanhe as últimas melhorias, novidades e recursos lançados na plataforma OdontoLead AI.
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

            @if ($releases->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Nenhuma novidade publicada ainda</h3>
                    <p class="text-sm text-slate-500 mt-1">Fique atento! Em breve traremos melhorias e novidades para a sua clínica.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($releases as $release)
                        @php
                            $isRead = $release->userReleaseReads->isNotEmpty();
                        @endphp
                        <article class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition hover:border-slate-200">
                            <!-- Release Card Header -->
                            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ ! $isRead ? 'bg-indigo-50/30' : '' }}">
                                <div class="flex items-start sm:items-center gap-3">
                                    <span class="px-3 py-1 bg-indigo-600 text-white font-mono font-bold text-xs rounded-lg shadow-sm">
                                        {{ $release->version }}
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-lg font-bold text-slate-900">{{ $release->title }}</h3>
                                            @if ($release->is_major)
                                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold uppercase rounded-md">
                                                    Versão Maior
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Publicado em {{ $release->released_at ? $release->released_at->format('d/m/Y \à\s H:i') : 'Data não informada' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if ($isRead)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Lida
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                            Não lida
                                        </span>
                                        <form method="POST" action="{{ route('app.releases.read', $release) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                                Marcar como lida
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Release Content -->
                            <div class="p-6 sm:p-8 prose prose-slate max-w-none text-sm leading-relaxed text-slate-700">
                                {!! nl2br(e($release->content)) !!}
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($releases->hasPages())
                    <div class="mt-6">
                        {{ $releases->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>

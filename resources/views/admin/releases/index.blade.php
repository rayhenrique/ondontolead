<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    {{ __('Novidades e Changelog do Sistema') }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Publique notas de versão com Markdown. As releases marcadas para exibir modal aparecerão aos usuários logados.</p>
            </div>
            <a href="{{ route('admin.releases.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Nova Release</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium rounded-2xl flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50/80 text-slate-400 uppercase text-[10px] font-mono tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5 text-left font-bold">Versão</th>
                            <th class="px-6 py-3.5 text-left font-bold">Título da Atualização</th>
                            <th class="px-6 py-3.5 text-left font-bold">Dispara Modal</th>
                            <th class="px-6 py-3.5 text-left font-bold">Leituras Registradas</th>
                            <th class="px-6 py-3.5 text-left font-bold">Data de Lançamento</th>
                            <th class="px-6 py-3.5 text-right font-bold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($releases as $release)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-sky-50 text-sky-700 border border-sky-200/80">
                                        {{ $release->version }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $release->title }}</div>
                                    <div class="text-xs text-slate-400 truncate max-w-md mt-0.5">{{ Str::limit(strip_tags($release->content), 80) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($release->show_modal)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Sim (Modal)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                                            Apenas Changelog
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-700">
                                    {{ $release->user_release_reads_count }} leitura(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-mono">
                                    {{ $release->released_at?->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                    <a href="{{ route('admin.releases.edit', $release) }}" class="text-[11px] font-bold text-sky-700 hover:text-sky-800 bg-sky-50 hover:bg-sky-100 px-2.5 py-1.5 rounded-lg border border-sky-200/60 transition">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.releases.destroy', $release) }}" class="inline" onsubmit="return confirm('Excluir esta release? Leituras de usuários associadas também serão apagadas.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg border border-rose-200/60 transition cursor-pointer">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                    Nenhuma release cadastrada ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($releases->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $releases->links() }}
                </div>
            @endif
        </div>

    </div>
</x-admin-layout>

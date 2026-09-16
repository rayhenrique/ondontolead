<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">
                    Editar Release: {{ $release->version }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Atualize as notas da versão e o comportamento de disparo do modal.</p>
            </div>
            <a href="{{ route('admin.releases.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition">
                <span>&larr;</span>
                <span>Voltar para Releases</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xs ring-1 ring-slate-900/5">

            <form method="POST" action="{{ route('admin.releases.update', $release) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Versão (SemVer) *</label>
                        <input type="text" name="version" value="{{ old('version', $release->version) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none font-mono" />
                        @error('version') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Data / Hora de Lançamento *</label>
                        <input type="datetime-local" name="released_at" value="{{ old('released_at', $release->released_at?->format('Y-m-d\TH:i')) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('released_at') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Título da Release *</label>
                        <input type="text" name="title" value="{{ old('title', $release->title) }}" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none" />
                        @error('title') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Conteúdo das Novidades (Markdown) *</label>
                        <textarea name="content" rows="8" required class="w-full text-xs bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-none font-mono">{{ old('content', $release->content) }}</textarea>
                        <p class="text-[11px] text-slate-400">Suporta formatação Markdown.</p>
                        @error('content') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 flex items-center pt-2">
                        <input type="hidden" name="show_modal" value="0" />
                        <label for="show_modal" class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" id="show_modal" name="show_modal" value="1" @checked(old('show_modal', $release->show_modal)) class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition" />
                            <span class="text-xs font-semibold text-slate-700">Disparar modal de novidades no login dos usuários da plataforma</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.releases.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-500/20 transition cursor-pointer">
                        Salvar Alterações
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-admin-layout>

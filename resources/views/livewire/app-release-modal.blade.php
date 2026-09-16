<div>
    @if ($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-100">
                    
                    <!-- Header with Gradient Accent -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5 text-white flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="bg-white/20 text-white font-mono text-xs font-bold px-2.5 py-0.5 rounded-full backdrop-blur-sm">
                                {{ $version }}
                            </span>
                            <span class="text-xs text-indigo-100">Lançada em {{ $releasedAt }}</span>
                        </div>
                        <span class="text-xs bg-emerald-400 text-emerald-950 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Novidade</span>
                    </div>

                    <div class="px-6 pt-6 pb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-3" id="modal-title">
                            {{ $title }}
                        </h3>

                        <!-- Content formatted -->
                        <div class="prose prose-sm max-w-none text-gray-600 bg-gray-50/75 p-4 rounded-xl border border-gray-100 max-h-80 overflow-y-auto leading-relaxed whitespace-pre-line text-xs font-sans">
                            {{ $content }}
                        </div>
                    </div>

                    <!-- Footer / Actions -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-100">
                        <a href="{{ route('app.releases.index') }}" wire:click="dismiss" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                            Ver histórico completo de versões &rarr;
                        </a>
                        <button type="button" wire:click="dismiss" class="w-full sm:w-auto inline-flex justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
                            Entendi, continuar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>

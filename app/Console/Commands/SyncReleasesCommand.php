<?php

namespace App\Console\Commands;

use App\Services\ReleaseSyncService;
use Illuminate\Console\Command;

class SyncReleasesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'releases:sync {--file= : Caminho customizado para o arquivo VERSOES.md}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza o histórico de versões do VERSOES.md com a tabela de novidades (app_releases)';

    /**
     * Execute the console command.
     */
    public function handle(ReleaseSyncService $service): int
    {
        $file = $this->option('file');
        $this->info('Iniciando sincronização de releases a partir do VERSOES.md...');

        $synced = $service->sync($file ? (string) $file : null);

        if (empty($synced)) {
            $this->warn('Nenhuma release encontrada para sincronizar.');

            return self::SUCCESS;
        }

        $headers = ['Versão', 'Data', 'Modal', 'Título'];
        $rows = array_map(function (array $item) {
            return [
                $item['version'],
                $item['released_at'],
                $item['show_modal'] ? 'Sim' : 'Não',
                $item['title'],
            ];
        }, $synced);

        $this->table($headers, $rows);
        $this->info('Total de '.count($synced).' releases sincronizadas com sucesso no banco de dados.');

        return self::SUCCESS;
    }
}

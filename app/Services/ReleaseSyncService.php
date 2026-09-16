<?php

namespace App\Services;

use App\Models\AppRelease;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class ReleaseSyncService
{
    /**
     * Sincroniza as releases descritas em VERSOES.md com a tabela app_releases.
     *
     * @return array<int, array{version: string, title: string, released_at: string, show_modal: bool}>
     */
    public function sync(?string $filePath = null): array
    {
        $path = $filePath ?? base_path('VERSOES.md');

        if (! File::exists($path)) {
            return [];
        }

        $markdown = File::get($path);
        $pattern = '/## (v[0-9]+\.[0-9]+\.[0-9]+)\s*—\s*([0-9]{4}-[0-9]{2}-[0-9]{2})(.*?)(?=(?:## v[0-9]+\.[0-9]+\.[0-9]+|\z))/s';

        preg_match_all($pattern, $markdown, $matches, PREG_SET_ORDER);

        $synced = [];
        $isFirst = true;

        foreach ($matches as $match) {
            $version = $match[1];
            $dateString = $match[2];
            $body = trim($match[3]);

            $title = "Release {$version}";
            if (preg_match('/-\s*\*\*Título:\*\*\s*(.+)/', $body, $titleMatch)) {
                $title = trim($titleMatch[1]);
            }

            $summary = '';
            if (preg_match('/-\s*\*\*Resumo:\*\*\s*(.+)/', $body, $summaryMatch)) {
                $summary = trim($summaryMatch[1]);
            }

            $added = '';
            if (preg_match('/### Adicionado\s*(.*?)(?=(?:###|\z))/s', $body, $addedMatch)) {
                $added = trim($addedMatch[1]);
            }

            $contentParts = [];
            if ($summary !== '') {
                $contentParts[] = $summary;
            }
            if ($added !== '') {
                $contentParts[] = "Principais novidades e melhorias:\n".$added;
            }

            $content = ! empty($contentParts)
                ? implode("\n\n", $contentParts)
                : $body;

            // Apenas a release mais recente (primeira da lista) dispara o modal
            $showModal = $isFirst;
            $isFirst = false;

            $releaseDate = Carbon::createFromFormat('Y-m-d', $dateString)->startOfDay();

            AppRelease::query()->updateOrCreate(
                ['version' => $version],
                [
                    'title' => $title,
                    'content' => $content,
                    'show_modal' => $showModal,
                    'released_at' => $releaseDate,
                ]
            );

            $synced[] = [
                'version' => $version,
                'title' => $title,
                'released_at' => $releaseDate->toDateString(),
                'show_modal' => $showModal,
            ];
        }

        return $synced;
    }
}

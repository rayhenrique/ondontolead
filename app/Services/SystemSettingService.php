<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class SystemSettingService
{
    public const CACHE_KEY = 'system_settings_cache';

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            $defaults = [
                'system_name' => config('app.name', 'OdontoLead AI'),
                'system_logo_url' => '',
                'system_favicon_url' => '',
                'system_footer_text' => '© '.date('Y').' OdontoLead AI. Todos os direitos reservados.',
            ];

            $saved = SystemSetting::query()
                ->pluck('value', 'key')
                ->toArray();

            return array_merge($defaults, $saved);
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        return $all[$key] ?? $default;
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    public function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            SystemSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value !== null ? (string) $value : null]
            );
        }

        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}

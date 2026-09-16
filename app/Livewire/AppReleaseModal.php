<?php

namespace App\Livewire;

use App\Models\AppRelease;
use App\Models\UserReleaseRead;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AppReleaseModal extends Component
{
    public bool $isOpen = false;

    public ?int $releaseId = null;

    public ?string $version = null;

    public ?string $title = null;

    public ?string $content = null;

    public ?string $releasedAt = null;

    public function mount(): void
    {
        $user = auth()->user();

        if ($user === null) {
            return;
        }

        $releaseId = session('unread_app_release_id');

        if ($releaseId === null) {
            // Check if there is an unread release directly
            $release = AppRelease::query()
                ->where('show_modal', true)
                ->where('released_at', '<=', now())
                ->whereDoesntHave('userReleaseReads', function ($query) use ($user): void {
                    $query->where('user_id', $user->id);
                })
                ->latest('released_at')
                ->first();

            if ($release !== null) {
                $releaseId = $release->id;
            }
        } else {
            $release = AppRelease::query()->find($releaseId);
        }

        if ($release !== null) {
            $alreadyRead = UserReleaseRead::query()
                ->where('user_id', $user->id)
                ->where('app_release_id', $release->id)
                ->exists();

            if (! $alreadyRead) {
                $this->releaseId = $release->id;
                $this->version = $release->version;
                $this->title = $release->title;
                $this->content = $release->content;
                $this->releasedAt = $release->released_at?->format('d/m/Y');
                $this->isOpen = true;
            }
        }
    }

    public function dismiss(): void
    {
        $user = auth()->user();

        if ($user !== null && $this->releaseId !== null) {
            UserReleaseRead::query()->firstOrCreate(
                [
                    'user_id' => $user->id,
                    'app_release_id' => $this->releaseId,
                ],
                [
                    'read_at' => Carbon::now(),
                ]
            );

            session()->forget('unread_app_release_id');
        }

        $this->isOpen = false;
    }

    public function render(): View
    {
        return view('livewire.app-release-modal');
    }
}

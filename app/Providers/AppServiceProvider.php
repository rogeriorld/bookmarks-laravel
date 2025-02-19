<?php

namespace App\Providers;

use App\Models\Bookmark;
use App\Models\Profile;
use App\Models\Tag;
use App\Observers\ProfileObserver;
use App\Observers\BookmarkObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Profile::observe(ProfileObserver::class);
        Bookmark::observe(BookmarkObserver::class);

        if (!Cache::has('cachedTags')) {
            // Recupera todas as tags do banco de dados
            $tags = Tag::all(['id', 'name']);

            // Descriptografa os valores das tags
            $decryptedTags = $tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            });

            // Armazena as tags descriptografadas no cache
            Cache::put('cachedTags', $decryptedTags);
        }

        if (!Cache::has('cachedProfiles')) {
            updateProfileCache();
        }

        if (!Cache::has('cachedBookmarks')) {
            updateBookmarkCache();
        }
    }
}

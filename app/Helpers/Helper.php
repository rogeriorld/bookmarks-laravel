<?php

use App\Models\Bookmark;
use App\Models\Profile;
use Illuminate\Support\Facades\Cache;

if (!function_exists('updateProfileCache')) {
    function updateProfileCache()
    {
        $profiles = Profile::all(['id', 'name', 'alt_name'])->sortBy('name');

        $decryptedProfiles = $profiles->map(function ($profile) {
            return [
                'id' => $profile->id,
                'name' => $profile->name,
                'alt_name' => $profile->alt_name,
            ];
        });

        Cache::put('cachedProfiles', $decryptedProfiles);
    }
}

if (!function_exists('updateBookmarkCache')) {
    function updateBookmarkCache()
    {
        $bookmarks = Bookmark::all(['id', 'url']);

        $decryptedBookmarks = $bookmarks->map(function ($bookmark) {
            return [
                'id' => $bookmark->id,
                'url' => $bookmark->url,
            ];
        });

        Cache::put('cachedBookmarks', $decryptedBookmarks);
    }
}

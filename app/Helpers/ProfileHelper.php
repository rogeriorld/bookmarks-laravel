<?php
namespace App\Helpers;
use App\Models\Profile;
use Illuminate\Support\Facades\Cache;

class ProfileHelper
{
    public static function updateProfileCache()
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
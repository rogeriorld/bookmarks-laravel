<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Bookmarks\Bookmark;
use App\Livewire\Profiles\Profile;
use App\Livewire\Settings;
use App\Livewire\Tags\Tag;

Route::middleware('auth')->group(
    function () {
        Route::get('/profiles', Profile::class)->name('profiles.index');
        Route::get('/settings', Settings::class)->name('settings.index');
        Route::get('/tags', Tag::class)->name('tags.index');

        Route::get('profiles/{profile}/bookmarks', Bookmark::class)->name('bookmarks.index');
    }
);
Route::get('/', function () {
    // Redirect to the profiles page
    return redirect()->route('profiles.index');
});


require __DIR__ . '/auth.php';

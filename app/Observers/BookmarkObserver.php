<?php

namespace App\Observers;

use App\Models\Bookmark;

class BookmarkObserver
{
    public function created(Bookmark $profile)
    {
        updateBookmarkCache();
    }

    public function updated(Bookmark $profile)
    {
        updateBookmarkCache();
    }
}

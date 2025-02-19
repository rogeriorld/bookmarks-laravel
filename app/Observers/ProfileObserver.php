<?php

namespace App\Observers;

use App\Models\Profile;

class ProfileObserver
{
    public function created(Profile $profile)
    {
        updateProfileCache();
    }

    public function updated(Profile $profile)
    {
        updateProfileCache();
    }
}

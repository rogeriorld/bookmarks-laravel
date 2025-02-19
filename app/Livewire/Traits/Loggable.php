<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Log;

trait Loggable
{
    public static function log($message)
    {
        Log::debug($message);
    }
}
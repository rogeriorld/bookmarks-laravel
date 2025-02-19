<?php

namespace App\Livewire\Traits;

trait OrderTrait
{
    public static $sortByList = ['name', 'bookmarks_count','created_at', 'updated_at'];
    public $orderByList = ['asc', 'desc'];
}

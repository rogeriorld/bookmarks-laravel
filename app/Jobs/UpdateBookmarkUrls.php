<?php

namespace App\Jobs;

use App\Models\Bookmark;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class UpdateBookmarkUrls implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $bookmark;
    protected $newUrl;
    /**
     * Create a new job instance.
     */

    public function __construct($bookmark, $newUrl)
    {
        $this->bookmark = $bookmark;
        $this->newUrl = Crypt::encrypt($newUrl);
    }

    public function handle()
    {
        Bookmark::where('id', $this->bookmark['id'])->update(['url' => $this->newUrl]);
    }
}

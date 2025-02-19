<?php

namespace App\Livewire;

use App\Jobs\UpdateBookmarkUrls;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Settings extends Component
{
    public $url, $urlMatch = '', $urlReplace = '';
    public $result, $resultReplace, $exists;
    public $cachedBookmarks;
    public $isModalOpen = false;
    public $dialogModal = [];

    public $profilesFound = [];

    public $listeners = ['confirmUpdateUrls' => 'confirmUpdateUrls'];

    public function mount()
    {
        $this->cachedBookmarks = Cache::get('cachedBookmarks');
    }

    public function check()
    {
        $this->validate([
            'url' => 'required|url',
        ]);

        // Check if the URL already exists in the bookmarks
        $this->exists = collect($this->cachedBookmarks)->contains('url', $this->url);
        $this->result = $this->exists ? 'This URL already exists in the bookmarks.' : 'This URL does not exist in the bookmarks.';
    }

    public function checkMatch()
    {
        $this->validate([
            'urlMatch' => 'required|url',
            'urlReplace' => 'required|url',
        ]);
        // Check if the url match exists in the bookmarks
        $this->exists = collect($this->cachedBookmarks)->firstWhere(function ($bookmark) {
            return strpos($bookmark['url'], $this->urlMatch) === 0;
        }) !== null;
        if ($this->exists) {
            // Get what profiles has the URL, if is more than one, need create a array with all the profiles
            // Get the total number of bookmarks that match the URL
            $matchingBookmarks = collect($this->cachedBookmarks)->filter(function ($bookmark) {
                return strpos($bookmark['url'], $this->urlMatch) === 0;
            });

            $total = $matchingBookmarks->count();

            $matchingBookmarks->map(function ($bookmark) {
                $profile = Bookmark::find($bookmark['id'])->profile;
                if (!in_array($profile->name, $this->profilesFound)) {
                    $this->profilesFound[] = $profile->name;
                }
            });

            $this->resultReplace = "Total bookmarks found: $total";

            // $newUrls = collect($this->cachedBookmarks)->filter(function ($bookmark) {
            //     return strpos($bookmark['url'], $this->urlMatch) === 0;
            // })->map(function ($item) {
            //     return [
            //         'id' => $item['id'],
            //         'url' => str_replace($this->urlMatch, $this->urlReplace, $item['url']),
            //     ];
            // });

            foreach ($matchingBookmarks as $bookmark) {
                $newUrl = str_replace($this->urlMatch, $this->urlReplace, $bookmark['url']);
                UpdateBookmarkUrls::dispatch($bookmark, $newUrl);
            }

            // show a modal dialog with the total number of bookmarks that will be updated
            // if the user confirms, update the bookmarks
            $this->dialogModal = ['title' => 'Update Bookmarks?', 'message' => 'Do you want to update the bookmarks?', 'total' => $total];
            $this->openModal($this->dialogModal);
        } else {
            $this->resultReplace = 'No URL match found in the bookmarks.';
        }
    }

    public function confirmUpdateUrls()
    {
        try {
            $this->updateUrls($this->dialogModal['newUrls']);
            $this->closeModal();
        } catch (\Exception $e) {
            $this->resultReplace = 'An error occurred while updating the bookmarks.';
        }

        $this->closeModal();
    }

    public function openModal($info)
    {
        $this->dialogModal = $info;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->reset(['urlMatch', 'urlReplace', 'resultReplace', 'exists', 'profilesFound', 'dialogModal']);
        $this->isModalOpen = false;
    }

    public function updateUrls($list)
    {
        // Update the bookmarks
        foreach ($list as $item) {
            $bookmark = Bookmark::find($item['id']);
            $bookmark->url = $item['url'];
            $bookmark->save();
        }
    }

    public function render()
    {
        return view('livewire.settings');
    }
}

<?php

namespace App\Livewire\Bookmarks;

use App\Livewire\Traits\FilterTrait;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use App\Models\Bookmark as BookmarkModel;
use App\Models\Profile;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;
use App\Helpers\CoverHelper;
use Illuminate\Support\Facades\Storage;

class Bookmark extends Component
{
    use WithPagination, WithoutUrlPagination;
    use FilterTrait;

    public Profile $profile;
    public int $selectedProfile;
    public mixed $bookmarkID = null;
    public bool $isModalOpen, $isModalMoveOpen = false;
    public string $searchTerm, $searchError;
    public array $searchResults = [];
    public object $tags;
    public array $listBookmarks = [];
    public string $batchUrls;

    public mixed $selectedTags = [];
    public mixed $selectedFilter = [];
    public mixed $filterTags = [];

    public $showCheckboxes = false;
    public $selectedBookmarks = [];

    public string $title;
    public string $url;
    public string $nameAutoComplete;

    public $listeners = ['updateTags' => 'updateTags'];

    public string $previewImageData = '';

    public string $directoryPath;

    public function mount(Profile $profile)
    {
        $this->profile = $profile;
        $this->updateListBookmarks();
        $this->updateTags();
        $this->directoryPath = "public/images/profiles/preview-{$this->profile->id}/";

        // Inicialize $filterTags com as tags do perfil + tags que cada bookmark tem
        $this->filterTags = $this->profile->tags->merge(
            $this->profile->bookmarks->flatMap(function ($bookmark) {
                return $bookmark->tags;
            })
        )->unique('id');
    }

    public function rules()
    {
        return [
            'title' => ['required', 'max:255'],
            'url' => ['required', 'url', function ($attribute, $value, $fail) {
                if ($this->bookmarkID) {
                    // Atualização: Verifica se a URL já existe para outro bookmark
                    if ($this->checkIfBookmarkExist($value, $this->bookmarkID)) {
                        $fail('The bookmark URL already exists.');
                    }
                } else {
                    // Criação: Verifica se a URL já existe
                    if ($this->checkIfBookmarkExist($value)) {
                        $fail('The bookmark URL already exists.');
                    }
                }
            }],
        ];
    }

    public function render()
    {
        // Get the id from the tag name 'Source'
        $sourceTag = Cache::get('cachedTags')->firstWhere('name', 'Source');
        $profiles = Cache::get('cachedProfiles')->where('id', '!=', $this->profile->id);

        $bookmarksSource = BookmarkModel::query();
        $bookmarksSource->whereHas('tags', function ($query) use ($sourceTag) {
            $query->where('tags.id', '=', $sourceTag['id']);
        })
            ->where('profile_id', $this->profile->id)
            ->orderBy('updated_at', 'desc');

        $bookmarks = BookmarkModel::query();
        // Exclude the bookmarks already taken in the source bookmarks
        $bookmarks->whereNotIn('id', $bookmarksSource->pluck('id'))
            ->where('profile_id', $this->profile->id)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->each(function ($bookmark) {
                $bookmark->cover = Storage::url($this->directoryPath . 'cover_image_' . $bookmark->id . '.cwebp');
            });

        // Se houver filtros selecionados, filtre os perfis com base nesses filtros
        if ($this->selectedFilter) {
            $tagIds = array_column($this->selectedFilter, 'id');
            foreach ($tagIds as $tagId) {
                $bookmarks->whereHas('tags', function ($query) use ($tagId) {
                    $query->where('tags.id', $tagId);
                });
            }
        }

        return view('livewire.bookmarks.bookmark', [
            'bookmarks' => $bookmarks->paginate(20),
            'bookmarksSource' => $bookmarksSource->paginate(10),
            'profiles' => $profiles,
            'directoryPath' => $this->directoryPath
        ]);
    }

    public function selectProfile($id)
    {
        // Verify if the selected profile exists
        $profile = Profile::find($id);
        if (!$profile) {
            $this->dispatch('notify', 'danger', 'The selected profile does not exist.');
            return;
        }
        $this->selectedProfile = $id;
    }

    public function changeBookmarkProfile()
    {
        try {
            if (empty($this->selectedBookmarks)) {
                $this->dispatch('notify', 'danger', 'No bookmarks selected to move.');
                return;
            }

            if (empty($this->selectedProfile)) {
                $this->dispatch('notify', 'danger', 'Please select a destination profile.');
                return;
            }

            $count = 0;
            foreach ($this->selectedBookmarks as $bookmarkId) {
                $book = BookmarkModel::find($bookmarkId);
                if ($book) {
                    $book->update([
                        'profile_id' => $this->selectedProfile,
                        'title' => $book->title
                    ]);
                    $count++;
                }
            }

            $this->dispatch('notify', 'success', "{$count} bookmarks moved successfully!");
            $this->selectedBookmarks = [];
            $this->closeModalMove();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', $e->getMessage());
        }
    }

    public function storeBookmarksBatch()
    {
        // $list é uma lista de bookmarks que terá apenas url, um por linha
        $list = explode("\n", $this->batchUrls);
        $list = array_map('trim', $list);
        // Para cada url, tente criar um novo bookmark
        foreach ($list as $url) {
            if (empty($url)) {
                continue;
            }
            try {
                // Ainda é necessário verificar se o bookmark já existe
                if ($this->checkIfBookmarkExist($url)) {
                    $this->dispatch('notify', 'danger', 'The bookmark URL already exists.');
                    continue;
                }

                $book = BookmarkModel::create([
                    'profile_id' => $this->profile->id,
                    'title' => $this->profile->name,
                    'url' => $url,
                ]);
                // para cada bookmark, deve associar as tags do perfil
                $book->tags()->attach(array_map(function ($tag) {
                    return $tag['id'];
                }, $this->profile->tags->toArray()));
                $this->dispatch('notify', 'success', 'Bookmarks created successfully!!');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
        $this->updateListBookmarks();
    }

    public function updateTags($exclude = null)
    {
        if ($exclude) {
            $this->tags = Tag::whereNotIn('id', $exclude)->get();
            return;
        }
        $this->tags = Tag::all();
    }

    public function updateListBookmarks()
    {
        $bookmarks = BookmarkModel::where('profile_id', $this->profile->id)->get();

        $this->listBookmarks = $bookmarks->mapWithKeys(function ($bookmark) {
            return [$bookmark->id => $bookmark->url];
        })->toArray();
    }

    public function addTag($tagID)
    {
        // Encontre a tag na coleção $tags
        $tag = $this->tags->firstWhere('id', $tagID);

        // Se a tag existir, adicione-a à coleção $selectedTags e remova-a de $tags
        if ($tag) {
            $this->selectedTags[] = $tag;
            $this->tags = $this->tags->filter(function ($tag) use ($tagID) {
                return $tag->id != $tagID;
            });
        }
    }

    public function removeTag($tagID)
    {
        // Encontre a tag na coleção $selectedTags
        $this->selectedTags = (object) $this->selectedTags;
        $tag = $this->selectedTags->firstWhere('id', $tagID);

        // Se a tag existir, adicione-a à coleção $tags e remova-a de $selectedTags
        if ($tag) {
            $this->tags->push($tag);
            $this->selectedTags = $this->selectedTags->filter(function ($tag) use ($tagID) {
                return $tag->id != $tagID;
            });
        }
    }

    public function store()
    {
        $this->validate();

        $this->checkIfBookmarkExist($this->url);
        try {
            $book = BookmarkModel::create([
                'profile_id' => $this->profile->id,
                'title' => $this->title,
                'url' => $this->url,
            ]);
            $this->selectedTags = (object) $this->selectedTags;
            $book->tags()->attach(array_map(function ($tag) {
                return $tag['id'];
            }, $this->selectedTags->toArray()));

            $this->isModalOpen = false;
            $this->reset(['bookmarkID', 'title', 'url', 'selectedTags']);
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', $e->getMessage());
        }
    }

    public function edit(BookmarkModel $bookmark)
    {
        try {
            $this->bookmarkID = $bookmark->id;
            $this->title = $bookmark->title;
            $this->url = $bookmark->url;
            $this->selectedTags = $bookmark->tags;
            // Converta a coleção para um array de IDs
            $selectedTagIds = $this->selectedTags->pluck('id')->toArray();
            // Agora $this->tags será uma coleção de tags que não estão em $selectedTags
            $this->tags = $this->tags->filter(function ($tag) use ($selectedTagIds) {
                return !in_array($tag->id, $selectedTagIds);
            });
            $this->isModalOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();
        $this->dispatch('updateTags');
        try {
            $this->checkIfBookmarkExist($this->url, $this->bookmarkID);
            $bookmark = BookmarkModel::findOrFail($this->bookmarkID);
            $bookmark->update([
                'title' => $this->title,
                'url' => $this->url,
            ]);
            // $selectedTagIds = $this->selectedTags->pluck('id')->toArray();
            // $bookmarkTagIds = $this->bookmark->tags->pluck('id')->toArray();
            $this->selectedTags = (object) $this->selectedTags;
            $bookmark->tags()->sync($this->selectedTags->pluck('id'));
            $this->dispatch('notify', 'success', 'Bookmark updated successfully!!');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', 'Was not possible update the bookmark!!');
        }
    }

    public function destroy(BookmarkModel $bookmark)
    {
        try {
            $bookmark->delete();
            $this->dispatch('notify', 'success', 'Bookmark deleted successfully!!');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', 'Was not possible delete the bookmark!!');
        }

        $this->updateListBookmarks();

        $this->reset(['bookmarkID', 'title', 'url']);
        $this->resetValidation();
    }

    public function openModal()
    {
        $this->title = $this->profile->name;
        $this->selectedTags = $this->profile->tags;
        $this->updateTags($this->selectedTags->pluck('id')->toArray());
        $this->isModalOpen = true;
    }

    public function openModalMove()
    {
        if (empty($this->selectedBookmarks)) {
            $this->dispatchBrowserEvent('toast', ['message' => 'Please select bookmarks to move', 'type' => 'warning']);
            return;
        }
        $this->isModalMoveOpen = true;
        // try {
        //     $this->bookmarkID = $id;
        // } catch (\Exception $e) {
        //     $this->dispatch('notify', 'danger', $e->getMessage());
        // }
        // $this->isModalMoveOpen = true;
    }

    public function closeModalMove()
    {
        $this->reset(['bookmarkID', 'selectedProfile']);
        $this->isModalMoveOpen = false;
        $this->resetValidation();
    }


    public function closeModal()
    {
        $this->dispatch('updateTags');
        $this->reset(['bookmarkID', 'title', 'url', 'selectedTags']);
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function checkIfBookmarkExist($url, $id = null)
    {
        $index = array_search($url, $this->listBookmarks);

        return $index !== false && ($id !== $index || $id === null);
    }

    // Function to get a screenshot of a website
    // Save the image in the public temp folder

    public function getScreenshot($close = false, $url = null)
    {
        if ($close) {
            $this->previewImageData = null;
            return;
        }
        try {
            $this->previewImageData = Browsershot::url($url)
                ->newHeadless()
                ->fullPage()
                ->setDelay(3000)
                ->base64Screenshot();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', $e->getMessage());
        }
    }

    public function getCover($id)
    {
        Log::info($id);
        $bookmark = BookmarkModel::find($id);
        if (!$bookmark) {
            $this->dispatch('notify', 'danger', 'The bookmark does not exist.');
            return;
        }
        $cover = new CoverHelper($bookmark->url, $bookmark->id, $this->directoryPath);
        $result = $cover->getCoverImage();
        Log::error($result);
        $this->dispatch('notify', $result['type'], $result['message']);
    }

    public function updatedPage($page)
    {
        $this->dispatch('pageBookmarkUpdated');
    }

    // Add this method to handle checkbox updates
    public function updatedSelectedBookmarks()
    {
        $this->dispatch('bookmarksSelected', count($this->selectedBookmarks));
    }

    public function showCheckbox()
    {
        Log::info('showCheckbox');
        $this->showCheckboxes = !$this->showCheckboxes;
        Log::info($this->showCheckboxes);
        $this->selectedBookmarks = [];
    }
}

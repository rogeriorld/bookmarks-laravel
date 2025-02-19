<?php

namespace App\Livewire\Profiles;

use App\Livewire\Traits\FilterTrait;
use App\Livewire\Traits\OrderTrait;
use App\Livewire\Traits\Loggable;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Profile as ProfileModel;
use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Helpers\ProfileHelper;

class Profile extends Component
{
    use WithPagination, WithoutUrlPagination;
    use FilterTrait;
    use WithFileUploads;
    use Loggable;

    public $profileID;
    public $cachedProfiles;
    public bool $isModalOpen = false;
    public $tags, $searchError;
    public bool $isLoading = false;
    public string $searchTerm = '';
    public mixed $filterTags = [];
    public mixed $selectedTags = [];
    public mixed $selectedFilter = [];
    public array $searchResults = [];
    public array $listProfiles = [];

    public $name;
    public $alt_name;
    public $image;
    public $existingImageBase64;

    public $sortByList = ['name', 'bookmarks_count', 'created_at', 'updated_at'];
    public $orderByList = ['asc', 'desc'];
    public $sortBy = 'bookmarks_count';
    public $orderBy = 'desc';

    public $listeners = ['updateTags' => 'updateTags', 'loading' => 'searchingProfile'];

    public function changeSortBy($field)
    {
        $this->dispatch('loading');
        if (in_array($field, $this->sortByList))
            $this->sortBy = $field;
    }

    public function changeOrderBy($field)
    {
        $this->dispatch('loading');
        if (in_array($field, $this->orderByList))
            $this->orderBy = $field;
    }

    public function updateTags($exclude = null)
    {
        if ($exclude) {
            $this->tags = Tag::whereNotIn('id', $exclude)->get();
            return;
        }
        $this->tags = Tag::all();
    }

    public function searchProfiles()
    {
        if (empty($this->searchTerm)) {
            $this->searchError = 'Please enter a search term.';
            return;
        }
        $this->dispatch('loading');
        $this->searchResults = [];
        $this->searchError = '';

        $searchTerm = trim($this->searchTerm);
        $foundProfiles = $this->cachedProfiles->filter(function ($profile) use ($searchTerm) {
            return str_contains(strtolower($profile['name']), strtolower($searchTerm)) ||
                str_contains(strtolower($profile['alt_name']), strtolower($searchTerm));
        });

        if (!$foundProfiles->isEmpty()) {
            $this->searchResults = $foundProfiles->pluck('id')->all();
        } else {
            $this->searchError = 'No profiles found for your search term.';
        }
        $this->dispatch('loading');
    }

    public function updatedSearchTerm()
    {
        $this->cachedProfiles = Cache::get('cachedProfiles');
        $this->resetPage();
    }

    public function mount()
    {
        $this->cachedProfiles = Cache::get('cachedProfiles');
        $this->updateTags();
        $this->filterTags = $this->tags;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'max:255', function ($attribute, $value, $fail) {
                if ($this->checkIfProfileExist($value, $this->profileID)) {
                    $fail('The profile name already exists.');
                }
            }],
            'alt_name' => ['max:255'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ];
    }

        public function render()
    {

        // Update the cache of profiles
        ProfileHelper::updateProfileCache();

        $profiles = ProfileModel::query();

        // If filters are selected, use those filters as the basis for profiles
        if ($this->selectedFilter) {
            $tagIds = array_column($this->selectedFilter, 'id');
            foreach ($tagIds as $tagId) {
                $profiles->whereHas('tags', function ($query) use ($tagId) {
                    $query->where('tags.id', $tagId);
                });
            }
        }

        $profiles->withCount('bookmarks');

        // Add the two tags with the lowest priority to the profile
        $profiles->with(['tags' => function ($query) {
            $query->orderBy('priority', 'asc')->limit(1);
        }]);

        // If the sortBy is name, decrypt the name and sort by it
        if ($this->sortBy === 'name') {
            $profiles = $profiles->get()->map(function (ProfileModel $profile) {
                // Find the matching profile in cachedProfiles
                $cachedProfile = collect($this->cachedProfiles)->firstWhere('id', $profile->id);

                // If a matching profile is found, use the decrypted name
                if ($cachedProfile) {
                    $profile->decrypted_name = $cachedProfile['name'];
                }

                return $profile;
            });

            $desc = $this->orderBy == 'desc' ? true : false;
            $profiles = $profiles->sortBy('decrypted_name', SORT_REGULAR, $desc);

            // Pagination
            $perPage = 24;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $profiles->slice($perPage * ($currentPage - 1), $perPage)->values();
            $profiles = new LengthAwarePaginator($currentItems, count($profiles), $perPage, $currentPage, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);
        } else {
            $profiles->orderBy($this->sortBy, $this->orderBy);

            // If there are search results, filter profiles based on those results
            if ($this->searchResults) {
                $profiles->whereIn('id', $this->searchResults);
            }
            // Order by the number of bookmarks by default
            $profiles->orderBy('bookmarks_count', 'desc');

        }
        $profiles = $profiles->paginate(24);

        // Loggable::log($profiles);

        return view('livewire.profiles.profile2', [
            'profiles' => $profiles
        ]);
    }

    public function updateListProfiles()
    {
        $profiles = ProfileModel::all();

        $this->listProfiles = $profiles->mapWithKeys(function ($profile) {
            return [$profile->id => $profile->name];
        })->toArray();
    }

    public function store()
    {
        $this->validate();
        $this->dispatch('updateTags');
        try {
            $this->checkIfProfileExist($this->name);

            if ($this->image) {
                $tempImage = $this->processImage($this->image);
            }

            $profile = ProfileModel::create([
                'name' => $this->name,
                'alt_name' => $this->alt_name,
                'image' => $tempImage,
            ]);
            $profile->tags()->attach(array_map(function ($tag) {
                return $tag['id'];
            }, $this->selectedTags));
            $this->dispatch('notify', 'success', 'Profile saved successfully!!');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $profile = ProfileModel::findOrFail($id);
            $this->selectedTags = $profile->tags;

            // Now $this->tags will be a collection of tags that are not in $selectedTags
            $this->dispatch('updateTags', $this->selectedTags->pluck('id'));

            $this->profileID = $id;
            $this->name = $profile->name;
            $this->alt_name = $profile->alt_name;
            $this->existingImageBase64 = $profile->image;
            $this->image = null;
            $this->openModal();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', $e->getMessage());
        }
    }


    public function update()
    {
        $this->validate();
        $this->dispatch('updateTags');
        try {
            $this->checkIfProfileExist($this->name, $this->profileID);
            $profile = ProfileModel::findOrFail($this->profileID);
            $tempImage = $profile->getImageName();

            if ($this->image) {
                // Remove the existing image
                if ($profile->getImageName()) {
                    unlink(storage_path('app/public/images/profiles/' . $profile->getImageName()));
                }

                $tempImage =
                    $this->processImage($this->image);
            }
            $profile->update([
                'name' => $this->name,
                'alt_name' => $this->alt_name,
                'image' => $tempImage,
            ]);
            $profile->tags()->sync($this->selectedTags->pluck('id'));
            // If a new tag is added, it should update all related bookmarks that do not already have that tag
            $profile->bookmarks->each(function ($bookmark) {
                $bookmark->tags()->sync($this->selectedTags->pluck('id'));
            });
            $this->dispatch('notify', 'success', 'Profile updated successfully!!');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', 'Was not possible update the profile!!');
        }
    }

    public function destroy($id)
    {
        try {
            $profile = ProfileModel::findOrFail($id);
            $profile->delete();
            $this->dispatch('notify', 'success', 'Profile deleted successfully!!');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', 'Was not possible delete the profile!!');
        }

        $this->reset(['profileID', 'name', 'alt_name']);
        $this->resetValidation();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->dispatch('updateTags');
        $this->reset(['profileID', 'name', 'alt_name', 'selectedTags', 'image', 'existingImageBase64']);
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function checkIfProfileExist($name, $id = null)
    {
        $index = array_search($name, $this->listProfiles);

        return $index !== false && ($id !== $index || $id === null);
    }

    public function addTag($tagID)
    {
        // Find a tag in collection $tags
        $tag = $this->tags->firstWhere('id', $tagID);

        // If the tag exists, add it to the $selectedTags collection and remove it from $tags
        if ($tag) {
            $this->selectedTags[] = $tag;
            $this->tags = $this->tags->filter(function ($tag) use ($tagID) {
                return $tag->id != $tagID;
            });
        }
    }

    public function removeTag($tagID)
    {
        $this->selectedTags = $this->selectedTags->filter(function ($tag) use ($tagID) {
            return $tag->id != $tagID;
        });

        $this->tags = Tag::whereNotIn('id', $this->selectedTags->pluck('id'))->get();
    }

    public function searchingProfile() {
        if ($this->isLoading) {
            $this->isLoading = false;
            return;
        }
        $this->isLoading = true;
    }


    private function processImage($image = null)
    {
        // If an image is provided, get the image content and encrypt it using encrypt()
        // Save this file in the public/images/profiles directory, the file name must be unique and need to have an extension .data
        // Return the name of the file saved
        if ($image) {
            $imageContent = file_get_contents($image->getRealPath());
            $imageName = uniqid("profile_") . '.data';
            $imagePath = storage_path('app/public/images/profiles/' . $imageName);
            file_put_contents($imagePath, encrypt($imageContent));
            return $imageName;
        }
    }
}

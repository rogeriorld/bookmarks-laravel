<?php

namespace App\Livewire\Tags;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Tag as TagModel;
use Livewire\Attributes\Validate;

class Tag extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $tagID;
    public $isModalOpen = false;
    public $listTags = [];


    protected $listeners = ['tagList' => 'updateListTags'];

    #[Validate('required|max:255')]
    public $name;

    #[Validate('required|hex_color')]
    public $color = '#C0C0C0';

    #[Validate('required|integer|between:0,9')]
    public $priority;

    public function updateListTags()
    {
        $tags = TagModel::all();

        $this->listTags = $tags->mapWithKeys(function ($tag) {
            return [$tag->id => $tag->name];
        })->toArray();
    }

    public function render()
    {
        $this->updateListTags();
        return view('livewire.tags.tag', [
            'tags' => TagModel::paginate(10),
        ]);
    }

    public function store()
    {
        $this->validate();
        $this->dispatch('tagList');
        // Check if $this->name exist in $this->listTags
        try {
            $this->checkIfTagExist($this->name);
            TagModel::create([
                'name' => $this->name,
                'color' => $this->color,
                'priority' => $this->priority
            ]);


            $this->dispatch('notify', 'success', 'Tag created successfully!!');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', 'Already exists a tag with this name!!');
        }
        $this->closeModal();
        $this->resetModel();
    }

    public function edit($id)
    {
        try {
            $tag = TagModel::findOrFail($id);
            $this->tagID = $tag->id;
            $this->name = $tag->name;
            $this->color = $tag->color;
            $this->priority = $tag->priority;
            $this->openModal();
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', 'Was not possible to find the tag!!');
        }
    }

    public function update()
    {
        $this->validate();
        $this->dispatch('tagList');
        try {
            if ($this->tagID) {
                $tag = TagModel::findOrFail($this->tagID);
                $this->checkIfTagExist($this->name, $this->tagID);

                $tag->update([
                    'name' => $this->name,
                    'color' => $this->color,
                    'priority' => $this->priority
                ]);

                $this->dispatch('notify', 'success', 'Tag updated successfully!!');
            }

            $this->resetModel();
            $this->closeModal();
        } catch (\Exception $e) {
            $this->addError('name', $e->getMessage());
            $this->dispatch('notify', 'danger', 'Was not possible to update the tag!!');
        }
    }

    public function destroy($id)
    {
        try {
            $tag = TagModel::findOrFail($id);
            $tag->delete();
            $this->dispatch('notify', 'success', 'Tag deleted successfully!!');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'danger', 'Was not possible to delete the tag!!');
        }

        $this->resetModel();
        $this->resetValidation();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetModel();
        $this->resetValidation();
    }

    private function resetModel()
    {
        $this->reset(['tagID', 'name', 'color', 'priority']);
    }

    private function checkIfTagExist($name, $id = null)
    {
        $index = array_search($name, $this->listTags);

        if ($index !== false && $id !== $index) {
            throw new \Exception('The tag name already exists!!');
        }

        if ($index !== false && $id === null) {
            throw new \Exception('Name already exists!!');
        }
    }
}

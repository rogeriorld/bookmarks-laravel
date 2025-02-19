<?php

namespace App\Livewire\Traits;

trait FilterTrait
{
    public mixed $selectedFilter = [];
    public mixed $filterTags = [];

    public function addFilter($tagID)
    {
        $tag = $this->filterTags->firstWhere('id', $tagID);

        if ($tag) {
            $this->selectedFilter[] = $tag;
            $this->filterTags = $this->filterTags->filter(function ($tag) use ($tagID) {
                return $tag->id != $tagID;
            });
        }
    }

    public function removeFilter($tagID)
    {
        $index = array_search($tagID, array_column($this->selectedFilter, 'id'));

        if ($index !== false) {
            $tag = $this->selectedFilter[$index];
            unset($this->selectedFilter[$index]);
            $this->selectedFilter = array_values($this->selectedFilter);
            $this->filterTags[] = $tag;
        }
    }
}

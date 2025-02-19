<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profiles</li>
        </ol>
    </nav>
    <div wire:loading>
        Saving post...
    </div>
    <div class="row">
        <div class="col d-flex align-items-center">
            <h3 class="d-inline-block m-0">Profiles</h3>
            {{-- Adicione um span com o ícone de + --}}
            <a wire:click='openModal' class="btn-add">
                <img width="32" height="32"
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAALFklEQVR4nO2aeXAUZRrGB9cVrVXURf9YoFYl95CZSTKZIxcTQkggFwnJ5JrcCSEhqIRa5HCVXeSUSDgSDkEQXEISQhIDBhACSkBL3bVqLf5CEMRjucI/opzWb+vratyeIRzpSUC38lS9VZ3nfb/nfbqnj6+/jkbTj370ox/96EefYsiL+AydwqRhU1g9rIKOoRUcGzqFC0OncFWOC4KTclNYJWqHVOCt+S3juXL0z07mjWcm882zFaAmnqng1LMVVAktzW8DDBheTvzwMg57lIMiTnuUs3V4OS96lBH7XAXefy7nSa2dh0SIbcGJ3PAypg4vo14eo9Q4NHwScaKH5tcI33KM3mV84l0GIrzK6PIuY6XXJMzqFBngVYrFaxI1spak6z2Jjz1KCdL8WvBMPg9rS1mmLeW6thS0pXynnUilPoc/9FYPoaUtZZq2lO/lHte1E6n2fJ6BmvsJbTGe/hP5XDcR/Eu46j+R13tzx2/qN5lHdSUsEb3knv8aUYqH5n4gsIiQgCK6AorBUMxRfRGBtyyewwNBJVgDipgdUMT2gCKOBBRxwVDEVRFiW3CGYpoCipmlL8QixtxKTldAkKGIL+Xe56X6ewljETHGIi4aiyCoiFazg0Hd1ZlLGGYsYqGxiG9FbU8iqJBvjIUsMOQxtFttB4OMhbTJ9ReNxYzR3AtYizFbCrloKQRLIW/b5vCga40pl8GWAlZbCrki12Eu5Ji5kFpzIVkhRQSKGmMpvxchtiWugGxzAassBRz/ZVwBl8U4azF/dO1jt/M7SwFrpdoCfgwpILRvdz4Hz9A8ukLzISSfdd09kkLyyQzN57xccz00n7qeG2OANZ+w0Dy2yhpC61xYHund1Ybksf5GTXghwzV9AVs+D4fn8XlEHoTn0SqOvlPexoPheawWeakmlz2hDnzc7TsyF7+IXPb9optHjejl2jsijza55rOxffF0sOWwLDIHInM4OtblmrfaeSQyhx1y/idbNhN7vX8uZZEOLkk9HLSJnsp8tJ3HbQ6Oibwth6pebR6TjXG0g+vR2Vwdnel8txdHP9rBjmgHjHZwNjqXYE0fISoLc7SDc3KvNtczQfiMdnBNxJhsAnqpLQNis/kkNhtisnndNRuTxWo5dzYmC19NHyM6A7+YbM6JnrHZrHTNx2axVMplcahXGsZlEh+XCeMy+G6MyyQnPp1MKZfJT+My++6X78aTJS6DS6L32EzSlLmkJB4bl8l/RC4+i1i3myWkczgxAxIyqVTyyckMTsjgvMjF23v/mr8TEjMoF70TMzgbn82TTp4zmC7l0jnoVpOUdAwpdki20+X666eksVrkUuzsUaM99SMOV34EIqYeplONRrKdDtmD06Vgt/NoShoXpFw6/hq1SE3ljbQ0SE11bpCSwrDUVK6kpnLdblf3qJtxGJShRsNuRys8pKVxOTOTIS7eV8neF2vUIj2VbzJSIT0Fkwu/UPAZE6hTq/1KJyhDrU7GBBokjxOY7+QxBavEp3JSlbAjCR9HCmQnc0Y545szhwccKZwSuaxkQtQaf+0gKEOtjmM8EbLPb50nZwxwpHBa5HIS8eyxcG4yZXnJkDeerUo+PwWr4HPHc8yd1ZnFH4Iy1OoID7nJnJC8JjsvvuSOp0Hw+cmU9li2IIlVhUlQkMSLSr4wiZcFX5hErXrTGs3SA6AMd7QKE1kjeU1khovXSpmv6bFoSQL7SxKhJN75WVqSwHaJTyTLHdM1+0EZ7miVxOMQnooT2abki+OJk/gE9vZYtCyeE2UJUDrWecVlUjxHBF+WdJsFkLvAmx2gDHe0yuMxSp4S+MLFq5fgJyVwvMeik+PoqoiDyhjn9/AbfEUyg283/u33Ofz2XuiN2Lj39vOE58fytORpHOeUfGkCT3XH3xWeH8uVF8bBHDsP3Q3virr3oTfjDl4HCk8vjOPy3fB3hakxXKmMvXlHb8W7omkP9Gbc6QAIT5UxNx+A7vi7wvQxdE2PufkSuMHPHH37S6B1F53v7oJeifbbz+lnjeVp4Wl6jPOpPs3GU93xd4UZ0Xw1cwzMjHGeRMyM5ojgZ4x27ya4qx2U4Y7WzBiMktcxzjfB6dF4yfyxHou+PJr9L4+G2dHOj8HZUWwX/Kxost0x3bETlOGO1qwociSvo50fg38dRZzER6l4DL4SxapXo+DVKOeJ0CtRzBa8yLtjunMHKMMdrVejWCt7esmJH0WlxI9SMRF6LZJJcyNhro16Jf/3SCwyfxw3psKftIEy1OoID3+3cVJ4mmdzXpSZG0mj4P9mU7FeMXcUPvNtMN/GGeWOztHwwHwbp6RcJGFqjX/eCspQq/NaBCOFl3k2vhbebvDC8zwbZ0XutVEqP58tCufUoghYEO786WlhOAsEvzDC+UWpJzjSAspQq7MogkbZyzwlv2AkITKv7nVYoCqcqqpwqAp3XhB5w8rQJWFcXhLG9eqR+GlU4GgzKEONxpIQ/KvC+bkqjEuLQ50XRKrCWS17X6RRi2Xh6KvDoDqMriVjnJfEqkOplXKhKu6wGo3mRBOdJ5tAxImmnq/diVN8aSgHhIelYSxX5haH8tjSUC6I3LIQN5bEBJaHcGhFCCy3ME2jwEoTg1eEcE7kVlop09xjrLAyRfJl5cyqcOdF0RVWXpJz7i2KCtRYiKu1Qo2V72tsPKpRoMZKupSzcGm1Ve1/gvQcK0MJqbVwWfSutTBBmVtuZlCtldNSzkyM283QMGCNiY/XmmGNiSWu+bUmakRurZlz6yzq7gc9wZsmRqw1c172s6wbP9WSH5O6leZuscFI0Lpgrq83cVVsK3NzbDy43kTbehOsN3HuLWPf/bPCehMh64I5L/dqbXT5QLs2GNP6YK6JeMuMoVebbzBSvTEYNgbz5Ttm54+jjVYe2WikTeQ3GLn0lpHy3uwtzsINRqZsDOay7KFV9FTWbDTwxEYjx6W88eYz1W0s92Tg5iD+uTkINgXRdsDlw6T4e1MQK0Veqgmk450AtO723RyM/6ZADih0l7v+8lLvQHbK3j5t1N7+VV01thrxqAvgfF0g1AWyvrupcF0AaXWBnJFrft4SQP0WI+E9mTaL2q1BRNQF0ig0hNaWQE7/I9D5hnejti6ADXLN2c16ntP0JeoDMNUb+KHBAA0GNrmeCQJbdDxZr2dlvYHLch31Br5q0LOm3oCjUYex2cRg8UuJ2GLkKcE1GMip17O2Qc+JG+MaDFyqN7C8xcATrn3EmVCv501JX8+PjYHqv1P0CNv9GdOk42KTHrbpaGs08nh3da2BDGnSM3+bnlOitofx9TY987Yb+VN32qLnNh075dofmnREa+4lWvVYmv0516KDFh3HWv1v/XlcvKQ0GzA3+zOz2Z9tzf580aKjq0XHFTm6mnX8W+RadMzYPgKT8sXGFSLfouO41Nufs0Jbcz+wU8fwNi2f7RgBbVqu7RjB0lYfHuurfu2eDGrTUi33YoeWT9/t62v+Tmj3ZGC7H1Xv+XGtXQvtfpxu9+MvB7TOs0Z3ILR2+TH9PT/OiB6i13talvTZ3V4N9mgJ2O3LoT2+IIUPF3b7UPu+L1Y1CydizC5fQvb4skpo3dDd7Uvnbq9enuT0Jvb7ENvhw8EOH7gR+3w4u8+Hhg5vKvd5M67DB5+9vv97CohtwYncXm+mdfjQKMYoNSRN316Y298rHPDF/wNvXv/Ai5MfeIOq8OLkAS8WCy3NbxkfeeB50IOJnR7Udnqw96AnXx70oOugB1fk6Or05KjIiZoPPSgRY+637370ox/96Ifm/xz/BSpr7w8S56dDAAAAAElFTkSuQmCC">
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6">
            @if ($isLoading)
            <div class="loading-indicator">
                <x-loading />
            </div>
            @endif
            <div class="input-group mb-1">
                <input wire:keydown.enter='searchProfiles' onkeyup="outFocus(event.keyCode)" wire:model='searchTerm'
                id="search" name="search" type="text" class="form-control"
                placeholder="Search for profiles..." aria-label="Search for profiles" />
                <button wire:click='searchProfiles' class="btn btn-outline-secondary" type="button"
                id="button-addon2">Search</button> {{ $isLoading }}
            </div>



            <script>
                function outFocus(key) {
                    if (key === 13)
                    document.getElementById('search').blur();
                }
            </script>
            <script>
                function showLoading(event) {
                    const imageContainer = document.getElementById('loading-container');
                    imageContainer.classList.remove('loaded');
                    imageContainer.classList.add('loading');
                }

                function loadedImage() {
                    const imageContainer = document.getElementById('loading-container');
                    imageContainer.classList.remove('loading');
                    imageContainer.classList.add('loaded');
                }
            </script>
            @if ($searchError)
            <div class="mb-2 d-block result-error">
                <span class="text-danger">{{ $searchError }}</span>
            </div>
            @endif
        </div>
    </div>


    @if ($selectedFilter)
    <div class="row">
        <div class="col-12">
            <div class="input mb-3">
                @foreach ($selectedFilter as $filter)
                <span class="badge rounded-pill badge-tag" style="background-color: {{ $filter->color }}">
                    {{ $filter->name }}
                    <button wire:click.prevent="removeFilter({{ $filter->id }})"
                        class="btn-close btn-close-white" aria-label="Remove"></button>
                    </span>
                    @endforeach
                </div>
            </div>
            <div class="col-5 col-md-3">
                <button wire:click="clearFilters" class="btn btn-danger">Clear Filters</button>
            </div>
            @endif

            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="tags">Filter:</label>
                        <select wire:model="filterTags" wire:change="addFilter($event.target.value)" class="form-select"
                        id="tagsSearch">
                        <option value="">Select a tag</option>
                        @foreach ($filterTags as $tag)
                        <option wire:key="tag-{{ $tag->id }}" value="{{ $tag->id }}">{{ $tag->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="sort">Sort:</label>
                    <select wire:change='changeSortBy($event.target.value)' wire:model="sortBy" class="form-select"
                    id="sortBy">
                    <option value="name">Name</option>
                    <option value="bookmarks_count">Bookmarks count</option>
                    <option value="created_at">Created at</option>
                    <option value="updated_at">Updated at</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="input-group mb-3">
                <label class="input-group-text" for="sort">Order:</label>
                <select wire:change='changeOrderBy($event.target.value)' wire:model="orderBy" class="form-select"
                id="orderBy">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </div>
    </div>
</div>

<div class="row justify-center">
    @foreach ($profiles as $profile)
    <div class="col-12 col-sm-6 col-lg-4 col-xl-2 mb-4">
        <div class="profile-card">
            <div class="profile-edit">
                <button wire:click="edit({{ $profile->id }})" class="">Edit</button>
            </div>
            {{-- <a href="{{ route('bookmarks.index', $profile->id) }}"> --}}
                <div class="image">
                    <div class="card-image-effect" {{-- linear-gradient(to bottom, transparent,#000000b8), --}}
                    style="background-image: url('{{ $profile->image ? 'data:image/jpeg;base64,' . $profile->image : 'https://placehold.co/580x400/grey/white' }}');">
                </div>
            </div>
            <div class="info">
                <a href="{{ route('bookmarks.index', $profile->id) }}">
                    <div class="profile-name">
                        <span>{{ $profile->name }}</span>
                    </div>
                    <div style="position: relative">
                        <span>Books - ({{ $profile->bookmarks_count }})</span>
                    </div>
                </a>
                <div class="profile-tag">
                    {{-- Obter as duas tags com menor prioridade --}}
                    @if ($profile->tags->count() > 1)
                    <span class="badge rounded-pill badge-tag"
                    style="background-color: {{ $profile->tags[0]->color }}">
                    {{ $profile->tags[0]->name }} / {{ $profile->tags[1]->name }}
                </span>
                @endif
            </div>
        </div>
        {{-- </a> --}}
    </div>
</div>
@endforeach
{{ $profiles->links() }}
</div>


@if ($isModalOpen)
<div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
aria-hidden="true" style="display: block;">
<div class="modal-dialog">
    <div class="modal-content">
        <form wire:submit="{{ $profileID ? 'update' : 'store' }}">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ $profileID ? 'Update tag' : 'Create tag' }}</h5>
                    <button wire:click="closeModal" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input wire:model="name" type="text"
                        class="form-control @error('name') is-invalid @enderror" id="name"
                        placeholder="Enter name here">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="alt_name" class="form-label">Alternative names:</label>
                        <input wire:model="alt_name" type="text"
                        class="form-control @error('alt_name') is-invalid @enderror" id="alt_name"
                        value="">
                        @error('alt_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">

                        <label for="image" class="form-label">Image:</label>
                        <input wire:model="image" type="file"
                        class="form-control @error('image') is-invalid @enderror" id="image"
                        onchange="showLoading(event)" />
                        <div id="loading-container" class="loaded">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        @if ($existingImageBase64 && !$image)
                        <img src="data:image/jpeg;base64,{{ $existingImageBase64 }}"
                        class="img-fluid img-thumbnail" alt="Cover Image">
                        @endif

                        @if ($image)
                        <img id="tempImage" src="{{ $image->temporaryUrl() }}"
                        class="img-fluid img-thumbnail" alt="Cover Image" onload="loadedImage()">
                        @endif

                        @error('image')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags:</label>
                        <select wire:model="tags" wire:change="addTag($event.target.value)"
                        class="form-control form-select" id="tags">
                        <option value="">Select a tag</option>
                        @foreach ($tags as $tag)
                        <option wire:key="tag-{{ $tag->id }}" value="{{ $tag->id }}">
                            {{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selected Tags:</label>
                        <div>
                            @foreach ($selectedTags as $tag)
                            <span class="badge rounded-pill badge-tag"
                            style="background-color: {{ $tag->color }}">
                            {{ $tag->name }}
                            <button wire:click.prevent="removeTag({{ $tag->id }})"
                                class="btn-close btn-close-white" aria-label="Remove"></button>
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                    <button type="submit"
                    class="btn btn-primary">{{ $profileID ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endif
</div>

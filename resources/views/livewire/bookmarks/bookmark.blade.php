<div>
    <x-breadcrumb :profile="$profile" />
    <div class="row mb-3 d-flex justify-content-end">
        <div class="col-1">
            <a wire:click='openModal' class="btn btn-primary">Create</a>
        </div>
        <div class="col-1">
            <a wire:click='openModalMove' class="btn btn-primary {{ $showCheckboxes ? 'active' : '' }}">
                Move {{ !empty($selectedBookmarks) ? '(' . count($selectedBookmarks) . ')' : '' }}
            </a>
            <a wire:click='showCheckbox()' class="btn btn-primary {{ $showCheckboxes ? 'active' : '' }}">
                Move {{ !empty($selectedBookmarks) ? '(' . count($selectedBookmarks) . ')' : '' }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <form wire:submit.prevent="storeBookmarksBatch">
                <div class="input-group mb-3">
                    <textarea wire:model="batchUrls" class="form-control" rows="3" placeholder="Enter with the url list"></textarea>
                    <button type="button" wire:click='storeBookmarksBatch()' class="btn btn-outline-secondary"
                        id="button-addon2">Add Batch</button>
                </div>
            </form>
        </div>
    </div>

    @if (count($bookmarksSource))
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4>Sources</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th scope="col-1">#</th>
                                    <th scope="col-3">Bookmark</th>
                                    <th scope="col-3">URL</th>
                                    <th scope="col-3">Tags</th>
                                    <th scope="col-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookmarksSource as $bookmark)
                                    <tr wire:key='{{ $bookmark->id }}'>
                                        <th scope="row">
                                            {{ $loop->iteration }}
                                        </th>
                                        <td>
                                            <a href="{{ $bookmark->url }}" target="_blank">
                                                {{ $bookmark->title }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $bookmark->url }}
                                        </td>
                                        <td>
                                            {{ $bookmark->tags->count() }}
                                        </td>
                                        <td>
                                            <button wire:loading wire:click="edit({{ $bookmark->id }})"
                                                class="btn btn-sm btn-primary">Edit</button>
                                            <button wire:click="destroy({{ $bookmark->id }})"
                                                class="btn btn-sm btn-danger"
                                                wire:confirm="Are you sure you want to delete this bookmark?">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $bookmarksSource->links() }}
                    </div>
                </div>
            </div>
        </div>

        <hr />
    @endif

    <div class="row">
        <div class="col-4">
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
    </div>
    @if ($selectedFilter)
        <div class="row">
            <div class="col-4">
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
    @endif

    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th scope="col-1">#</th>
                                <th scope="col-1">Cover</th>
                                <th scope="col-3">Bookmark</th>
                                <th scope="col-3">URL</th>
                                <th scope="col-3">Tags</th>
                                <th scope="col-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookmarks as $bookmark)
                                <tr wire:key='{{ $bookmark->id }}' class="bookmark-row">
                                    <th scope="row">
                                        <div class="form-check">
                                            <label class="form-check-label"
                                                for="checkbox-{{ $bookmark->id }}">{{ $bookmark->id }}</label>
                                            <input class="form-check-input" type="checkbox"
                                                wire:model.live="selectedBookmarks" value="{{ $bookmark->id }}"
                                                id="checkbox-{{ $bookmark->id }}">
                                        </div>

                                        {{-- <span>{{ $loop->iteration }}</span> --}}
                                    </th>
                                    <td>
                                        @if ($bookmark->cover_image)
                                            <x-bookmark.cover-image :cover="$bookmark->cover_image" />
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $bookmark->url }}" target="_blank">
                                            {{ $bookmark->title }}
                                        </a>
                                    </td>
                                    <td>
                                        @if (strlen($bookmark->url) > 35)
                                            {{ substr($bookmark->url, 0, 35) . '...' }}
                                        @else
                                            {{ $bookmark->url }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $bookmark->tags->count() }}
                                    </td>
                                    <td>
                                        <button wire:click="getCover('{{ $bookmark->id }}')"
                                            class="btn btn-sm btn-primary">Screenshot</button>
                                        <button wire:click="edit({{ $bookmark->id }})"
                                            class="btn btn-sm btn-primary">Edit</button>
                                        <button wire:click="openModalMove({{ $bookmark->id }})"
                                            class="btn btn-sm btn-secondary">Move</button>
                                        <button wire:click="destroy({{ $bookmark->id }})" class="btn btn-sm btn-danger"
                                            wire:confirm="Are you sure you want to delete this bookmark?">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $bookmarks->links() }}
                </div>
            </div>
        </div>
    </div>
    @if ($previewImageData)
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <img src="data:image/png;base64,{{ $previewImageData }}" class="img-fluid" alt="Screenshot">
                    <div class="modal-footer">
                        <button wire:click="getScreenshot(true)" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
    @endif
    @if ($isModalMoveOpen)
        <div class="modal fade show" id="modalMove" tabindex="-1" aria-labelledby="modalMove" aria-hidden="true"
            style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="my-3">
                        <p>Moving {{ count($selectedBookmarks) }} bookmark(s)</p>
                        <div class="selected-bookmarks mb-3">
                            @foreach ($selectedBookmarks as $bookmarkId)
                                @php
                                    $bookmark = $bookmarks->firstWhere('id', $bookmarkId);
                                @endphp
                                @if ($bookmark)
                                    <div class="badge bg-secondary mb-1">{{ $bookmark->title }} - {{ $bookmark->id }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="form-control mb-3">
                        <label for="profile" class="form-label">Move to profile:</label>
                        <select name='profile' class="form-select" required
                            wire:change='selectProfile($event.target.value)'>
                            <option value="">-------</option>
                            @foreach ($profiles as $profile)
                                <option value={{ $profile['id'] }}>{{ $profile['name'] }}</option>
                            @endforeach
                        </select>
                        @error('profile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" wire:click='changeBookmarkProfile()'>Change</button>
                        <button type="submit" class="btn btn-danger" wire:click='closeModalMove'>Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
    @if ($isModalOpen)
        <div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit="{{ $bookmarkID ? 'update' : 'store' }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                {{ $bookmarkID ? 'Update bookmark' : 'Create bookmark' }}
                            </h5>
                            <button wire:click="closeModal" type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title: {{ $nameAutoComplete }}</label>
                                <input wire:model.lazy="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror" id="title"
                                    placeholder="Enter title here">
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">URL:</label>
                                <input wire:model="url" type="url"
                                    class="form-control @error('url') is-invalid @enderror" id="url"
                                    value="#000000">
                                @error('url')
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
                                            {{ $tag->name }}
                                        </option>
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
                                class="btn btn-primary">{{ $bookmarkID ? 'Update' : 'Create' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('scripts')
    <script>
        function initializeBookmarkCoverZoom() {
            document.querySelectorAll('.bookmark-cover-container').forEach(function(cover) {

                let zoomImage;
                // console.info('start');
                // cover.removeEventListener('mouseenter', handleMouseEnter);
                // cover.removeEventListener('mouseleave', handleMouseLeave);
                if (!cover.hasAttribute('data-mouseenter-added')) {
                    cover.addEventListener('mouseenter', handleMouseEnter);
                    cover.addEventListener('mouseleave', handleMouseLeave);
                    cover.setAttribute('data-mouseenter-added', 'true');
                }



                function handleMouseEnter(event) {

                    try {
                        const coverImage = cover.getAttribute('data-cover');

                        zoomImage = new Image();
                        zoomImage.src = coverImage;
                        zoomImage.onload = function() {
                            const screenWidth = window.innerWidth;
                            const screenHeight = window.innerHeight;

                            // let width = zoomImage.width;                            const coverImage = cover.getAttribute('data-cover');

                            let width = zoomImage ? zoomImage.width : 0;


                            let height = zoomImage ? zoomImage.height : 0;

                            let maxWidth = 0;
                            let maxHeight = 0;
                            if (width > height) {
                                maxWidth = screenWidth * 0.6;
                                maxHeight = 'auto';
                            } else {
                                maxWidth = 'auto';
                                maxHeight = screenHeight * 0.9;
                            }

                            zoomImage.classList.add('bookmark-cover-image-zoom');

                            if (width > height) {
                                zoomImage.style = `max-width: ${maxWidth}px; max-height: ${maxHeight}`;
                            } else {
                                zoomImage.style = `max-width: ${maxWidth}; max-height: ${maxHeight}px`;
                            }

                            document.body.appendChild(zoomImage);
                        };
                    } catch (error) {
                        console.error('Cross-origin image error:', error);
                    }

                }

                function handleMouseLeave() {
                    if (zoomImage) {
                        zoomImage.remove();
                        zoomImage = null;
                    }
                }

            });
        }
        Livewire.hook('element.init', ({
            component,
            el
        }) => {
            if (el.classList.contains('bookmark-row')) {
                initializeBookmarkCoverZoom();
            }
        });
    </script>
@endpush

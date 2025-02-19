<div>
    <div class="row">
        <div class="col-2">
            <h2>Profiles</h2>
        </div>
        <div class="col m-auto">
            <a wire:click='openModal' class="btn btn-primary">Create</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="input-group mb-3">
                <input wire:model='search' id="search" name="search" type="text" class="form-control"
                    placeholder="Search for profiles" aria-label="Search for profiles" aria-describedby="button-addon2">
                <button wire:click="searcher()" class="btn btn-outline-secondary" type="button"
                    id="button-addon2">Search</button>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Alternative names</th>
                                    <th scope="col">Bookmarks</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profiles as $profile)
                                    <tr wire:key='{{ $profile->id }}'>
                                        <th scope="row">
                                            {{ $loop->iteration }}
                                        </th>
                                        <td>
                                            <a wire:navigate href="/profiles/{{ $profile->id }}/bookmarks">
                                                {{ $profile->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill badge"
                                                style="font-size: .9rem; background-color: {{ $profile->alt_name }}">
                                                {{ $profile->alt_name }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $profile->bookmarks->count() }}
                                        </td>
                                        <td>
                                            <button wire:click="edit({{ $profile->id }})"
                                                class="btn btn-sm btn-primary">Edit</button>
                                            <button wire:click="destroy({{ $profile->id }})"
                                                class="btn btn-sm btn-danger"
                                                wire:confirm="Are you sure you want to delete this tag?">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $profiles->links() }}
                    </div>
                </div>
            </div>
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
                                        value="#000000">
                                    @error('alt_name')
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

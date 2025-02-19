<div>

    <div class="row">
        <div class="col-2">
            <h2>Tags</h2>
        </div>
        <div class="col m-auto">
            <a wire:click='openModal' class="btn btn-primary">Create</a>
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
                                <th scope="col">Color</th>
                                <th scope="col">Priority</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tags as $tag)
                                <tr wire:key='{{ $tag->id }}'>
                                    <th scope="row">
                                        <a wire:click.prevent="destroy( {{ $tag->id }})"
                                            wire:confirm="Are you sure you want to delete this tag?">{{ $loop->iteration }}</a>
                                    </th>
                                    <td>
                                        <a wire:click="edit({{ $tag->id }})">
                                            {{ $tag->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill"
                                            style="font-size: .7rem; background-color: {{ $tag->color }}">
                                            {{ $tag->color }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $tag->priority }}
                                    </td>
                                    <td>
                                        <button wire:click="edit({{ $tag->id }})"
                                            class="btn btn-sm btn-primary">Edit</button>
                                        <button wire:click="destroy({{ $tag->id }})" class="btn btn-sm btn-danger"
                                            wire:confirm="Are you sure you want to delete this tag?">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $tags->links() }}
                </div>
            </div>
        </div>
    </div>

    @if ($isModalOpen)
        <div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit="{{ $tagID ? 'update' : 'store' }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ $tagID ? 'Update tag' : 'Create tag' }}
                            </h5>
                            <button wire:click="closeModal" type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    placeholder="Enter name here">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col">
                                        <label for="color" class="form-label">Color</label>
                                        <input wire:model="color" type="color"
                                            class="form-control @error('color') is-invalid @enderror" id="color"
                                            value="#F0F0F0">
                                        @error('color')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="priority" class="form-label">Priority</label>
                                        <input type="number" min="0" max="9" value="9"
                                            wire:model="priority"
                                            class="form-control @error('priority') is-invalid @enderror" id="priority">
                                        @error('priority')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button wire:click="closeModal" type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $tagID ? 'Update' : 'Create' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

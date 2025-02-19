<div>

    <div class="row mt-5">
        <div class="offset-1 col-10">
            <h2 class="text-white">Update Tag</h1>
        </div>
    </div>
    <div class="row">

        <div class="offset-md-2 col-10 col-md-8">
            <form wire:submit.prevent>
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ $name }}">
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" class="form-control" id="color" name="color"
                            value="{{ $color }}">
                    </div>
                    <div class="col">
                        <label for="priority" class="form-label">Priority</label>
                        <input type="number" max="9" min="0" class="form-control" id="priority"
                            name="priority" value="{{ $priority }}">
                    </div>
                </div>
                <a wire:click.prevent="update($id)" class="btn btn-primary">Update</a>
            </form>
        </div>
    </div>

</div>

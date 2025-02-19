<div>
    <div class="row mt-5">
        <div class="offset-1 col-10">
            <h2 class="text-white">Create Tag</h1>
        </div>
    </div>
    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col">
            <form>
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value=""
                        placeholder="Tag name">
                    <div>
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" class="form-control" id="color" name="color" value="#000000">
                        <div>
                            @error('color')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <label for="priority" class="form-label">Priority</label>
                        <input type="number" max="9" min="0" class="form-control" id="priority"
                            name="priority" value="9">
                        <div>
                            @error('priority')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" wire:click.prevent="store()" class="btn btn-primary">Store</button>
            </form>
        </div>
    </div>

</div>

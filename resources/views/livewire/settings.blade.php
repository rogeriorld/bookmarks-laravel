<div>
    <div class="row">
        <div class="col">
            <h1>Settings</h1>
        </div>
    </div>
    <div class="row align-items-start">
        <div class="col col-md-4 settings-box">
            <h5>Validate</h5>
            <form wire:submit.prevent class="form-inline">
                <div class="mb-3">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" class="form-control" id="url" wire:model="url"
                        placeholder="Insert the url...">
                    @error('url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if ($result)
                    <div class="mb-4">
                        <div class="{{ $exists ? 'text-danger' : 'text-success' }}" role="alert">
                            <strong>{{ $result }}</strong>
                        </div>
                    </div>
                @endif
                <button type="button" wire:click='check' class="btn btn-success">Check</button>
            </form>
        </div>
        <div class="col-1">&nbsp;</div>
        <div class="col settings-box">
            <form wire:submit.prevent class="form-inline">
                <h5>Replacer</h5>
                <div class="mb-3">
                    <label for="urlMatch" class="form-label">Url to change:</label>
                    <input type="urlMatch" class="form-control" id="urlMatch" wire:model="urlMatch"
                        placeholder="Insert the url...">
                    @error('urlMatch')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="urlReplace" class="form-label">Replace for:</label>
                    <input type="url" class="form-control" id="urlReplace" wire:model="urlReplace"
                        placeholder="Url to change...">
                    @error('urlReplace')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if ($resultReplace)
                    <div class="mb-4">
                        <div class="{{ $exists ? 'text-danger' : 'text-success' }}" role="alert">
                            <strong>{{ $resultReplace }}</strong>
                        </div>
                    </div>
                @endif
                <button type="button" wire:click='checkMatch' class="btn btn-success">Find</button>
            </form>

        </div>
    </div>

    @if ($isModalOpen)
        <div class="modal fade show d-block" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{ $dialogModal['title'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click='closeModal'></button>
                    </div>
                    <div class="modal-body">
                        <h5>{{ $dialogModal['message'] }}</h5>
                        <p>Total: {{ $dialogModal['total'] }}</p>
                        <p>Profiles:</p>
                        @foreach ($profilesFound as $profile)
                            <p>{{ $profile }}</p>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click='closeModal'>Close</button>
                        <button type="button" class="btn btn-primary" wire:click='confirmUpdateUrls'>Update</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

</div>

<div style="position: fixed;
    width: 300px;
    right: 50px;
    bottom: 50px;
    z-index: 40;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    align-content: flex-end;
    align-items: flex-end;
    justify-content: space-evenly;">
    @foreach ($messages as $message)
        <div class="alert alert-{{ $message['type'] }} alert-dismissible fade show" role="alert"
            id="{{ $message['id'] }}">
            {{ $message['message'] }}
            <button wire:click="hideMessage('{{ $message['id'] }}')" type="button" class="btn-close"
                data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('messageAdded', function(event) {
            setTimeout(function() {
                Livewire.dispatch('hideMessage', event[0]);
            }, 2000);
        });
    });
</script>

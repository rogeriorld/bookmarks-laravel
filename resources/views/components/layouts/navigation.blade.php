<div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">| {{ config('app.name') }} |</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @if (Auth::check())
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profiles.index') }}"
                                wire:navigate>{{ __('Profiles') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tags.index') }}" wire:navigate>Tags</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('settings.index') }}" wire:navigate>Settings</a>
                        </li>

                        <li wire:click="logout" wire:confirm="{{ __('Are you sure you want to logout?') }}"
                            class="nav-link">
                            {{ __('Log Out') }}
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </nav>
</div>

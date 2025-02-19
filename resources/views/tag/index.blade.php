@livewire('components.layouts.app')
<x-slot>
    <div class="row">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Color</th>
                    <th scope="col">Priority</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tags as $tag)
                <tr>
                    <th scope="row">
                        {{ $loop->iteration }}
                    </th>
                    <td>
                        <a href="{{ route('tags.edit', $tag) }}">
                            {{ $tag->name }}
                        </a>
                    </td>
                    <td>
                        <span class="badge rounded-pill badge" style="font-size: 1rem; background-color: {{ $tag->color }}">
                            {{ $tag->color }}
                        </span>
                    <td>
                        {{ $tag->priority }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-slot>
@endlivewire
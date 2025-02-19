@extends('layout.main')
@section('content')
    <div class="row">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Bookmarks</th>
                    <th scope="col">Tags</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($people as $person)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</td>
                        <td><a href="{{ route('people.edit', $person) }}">
                                {{ $person->name }}
                            </a>
                        </td>
                        <td>
                            {{ count($person->bookmarks) }}
                        <td>
                            @foreach ($person->tags as $tag)
                                @if ($person->tags->contains($tag))
                                    <span
                                        style="padding: .2rem; border-radius: .5rem; color: #101010; font-weight: 800; background-color: {{ $tag->color }}; text-wrap:nowrap; font-size: .8rem;">{{ $tag->name }}</span>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

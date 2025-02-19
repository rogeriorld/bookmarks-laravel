@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Edit Person</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="{{ route('people.update', $person) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $person->name }}">
                    </div>
                    <div class="col mb-3">
                        <label for="alt_name" class="form-label">Alternative names:</label>
                        <input type="text" class="form-control" id="alt_name" name="alt_name"
                            value="{{ $person->alt_name }}">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="col">
                        Select the tags:
                        <div class="mb-1">
                            @foreach ($tags as $tag)
                                <span class="badge" style="font-size: 1rem; background: {{ $tag->color }}">
                                    {{ $tag->name }}
                                    <sup href="#" style="font-size: .8rem; cursor: pointer;"
                                        onclick="removeTag({{ $tag->id }})">x</sup>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="col">
                        <select class="form-select" id="tag_id" name="tags">
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="addTag()">Add</button>
                    </div>
                </div>
                {{-- Um campo select que seja multiplo --}}
                <select multiple class="form-select" id="tag_id" name="tags[]">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @if ($person->tags->contains($tag)) selected @endif>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection

@extends('layout.main')
@section('content')
<div class="row mt-5">
    <div class="offset-1 col-10">
        <h2 class="text-white">Edit Tag</h1>
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
    <div class="offset-md-2 col-10 col-md-8">
        <form action="{{ route('tags.update', $tag) }}" method="POST" class="text-white">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $tag->name }}">
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="color" class="form-label">Color</label>
                    <input type="color" class="form-control" id="color" name="color" value="{{ $tag->color }}">
                </div>
                <div class="col">
                    <label for="priority" class="form-label">Priority</label>
                    <input type="number" class="form-control" id="priority" name="priority" value="{{ $tag->priority }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection
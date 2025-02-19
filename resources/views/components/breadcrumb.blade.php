@props(['profile'])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="/profiles">Profiles</a></li>
        <li class="breadcrumb-item"><a href="/profiles/{{ $profile->id }}/bookmarks">{{ $profile->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bookmarks</li>
    </ol>
</nav>

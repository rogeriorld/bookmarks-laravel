<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    // Get all bookmarks from the database ordered by update time and group by tag Person.name
    function index()
    {
        $bookmarks = Bookmark::with('person')->orderBy('updated_at', 'desc')->group_by('person.name')->get();
        return view('bookmark.index', ['bookmarks' => $bookmarks]);        
    }

    function api_index()
    {
        $bookmarks = Bookmark::with('person')->orderBy('updated_at', 'desc')->group_by('person.name')->get();
        return response()->json($bookmarks);        
    }
}

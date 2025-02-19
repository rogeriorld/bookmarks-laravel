<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;


class TagController extends Controller
{

    public function search(Request $request)
    {
        // Validar se o campo searchTerm está presente, caso seja branco, retornar um erro
        $request->validate([
            'searchTerm' => 'required'
        ]);

        $searchTerm = $request->input('searchTerm');

        $tags = Tag::all();
        $tagsArray = $tags->mapWithKeys(function ($tag) {
            return [$tag->id => $tag->name];
        })->toArray();

        Cache::put('tags', $tagsArray);
        $tagsC = Cache::get('tags');

        $matches = array_filter($tagsC, function ($name) {
            return stripos($name, $searchTerm) !== false;
        });

        return response()->json($matches);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\View\View
    {
        $tags = Tag::orderBy('priority', 'asc')->get();
        return view('tag.index', ['tags' => $tags]);
    }

    public function apiIndex()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tags|max:255',
            'color' => 'required|hex_color',
            'priority' => 'integer'
        ]);

        $tag = Tag::create($request->all());
        return response()->json($tag);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tag = Tag::findOrFail($id);
        return view('tag.edit', ['tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:tags|max:255',
            'color' => 'required|hex_color',
            'priority' => 'integer'
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update($request->all());
        // Armazena a notificação na sessão
        session()->flash('noti-success', 'Tag updated successfully');
        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return response()->json(null, 204);
    }
}

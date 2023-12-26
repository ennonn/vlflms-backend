<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;

class AuthorsController extends Controller
{
    public function index(Request $request)
    {
        // Show data based on logged user
        $query = Author::where('user_id', $request->user()->id);

        // Check if a keyword is provided for search
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($query) use ($keyword) {
                $query->where('first_name', 'like', "%$keyword%")
                      ->orWhere('last_name', 'like', "%$keyword%");
            });
        }

        // Perform pagination
        $authors = $query->paginate();

        return $authors;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorRequest $request)
{
    $validated = $request->validated();

    // Add user_id to the validated data
    $validated['user_id'] = $request->user()->id;

    $author = Author::create($validated);

    return $author;
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Author::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorRequest $request, string $id)
    {
        $validated = $request->validated();

        // Get info by id
        $author = Author::findOrFail($id);

        // Update new info
        $author->update($validated);

        return $author;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);

        $author->delete();

        return $author;
    }
}

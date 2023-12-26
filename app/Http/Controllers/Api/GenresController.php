<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenreRequest;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Show data based on logged user
    $keyword = $request->input('keyword');
    $perPage = $request->input('per_page', 10);

    $genresQuery = Genre::where('user_id', $request->user()->id);

    if ($keyword) {
        // Add a condition to filter by genre name or any other relevant field
        $genresQuery->where('name', 'like', "%$keyword%");
    }

    // Use paginate() instead of get() to enable pagination
    $genres = $genresQuery->paginate($perPage);

    return $genres;
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreRequest $request)
{
    $validated = $request->validated();

    // Add user_id to the validated data
    $validated['user_id'] = $request->user()->id;

    $genre = Genre::create($validated);

    return $genre;
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Genre::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreRequest $request, string $id)
    {
        $validated = $request->validated();

        // Get info by id
        $genre = Genre::findOrFail($id);

        // Update new info
        $genre->update($validated);

        return $genre;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genre::findOrFail($id);

        $genre->delete();

        return $genre;
    }
}

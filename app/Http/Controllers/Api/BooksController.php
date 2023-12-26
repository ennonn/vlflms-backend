<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BookRequest;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource with page or keyword.
     */
    public function index(Request $request)
    {
        // Show data based on logged user
        $books = Book::where('user_id', $request->user()->id);

        // Cater Search use "keyword"
        if ($request->keyword) {
            $books->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }
        info('SQL Query: ' . $books->toSql());
        // Paginate based on the number set; You can change the number below
        return $books->paginate(10);
        
        // Show all data; Uncomment if necessary
        // return Book::all();
    }

    /**
     * Display all listing of the resource.
     */
    public function all(Request $request)
    {
        return Book::where('user_id', $request->user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
{
    $validated = $request->validated();

    // Upload image to backend and store image path
    if ($request->hasFile('image_path')) {
        $validated['image_path'] = $request->file('image_path')->storePublicly('books', 'public');
    }

    // Set the user_id based on the authenticated user
    $validated['user_id'] = auth()->user()->id;

    $book = Book::create($validated);

    return $book;
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Book::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, int $id)
{
    $validated = $request->validated();

    // Upload image to backend and update image path if a new image is provided
    if ($request->hasFile('image_path')) {
        $validated['image_path'] = $request->file('image_path')->storePublicly('books', 'public');
    }

    // Get the book by ID
    $book = Book::findOrFail($id);

    // Delete previous image if it exists
    if (!is_null($book->image_path)) {
        Storage::disk('public')->delete($book->image_path);
    }

    // Update book
    $book->update($validated);

    return $book;
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);

        // Delete associated images
        if (!is_null($book->image_path)) {
            Storage::disk('public')->delete($book->image_path);
        }

        $book->delete();

        return $book;
    }
}

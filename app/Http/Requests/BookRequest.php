<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
{
    return [
        'title' => 'required|string|max:255',
        'author_id' => 'required|exists:authors,id',
        'isbn' => 'required|string|unique:books,isbn,' . $this->route('book'), // Ensure unique validation excludes the current book
        'year_published' => 'required|numeric|digits:4',
        'description' => 'nullable|string',
        'genre_id' => 'required|exists:genres,id',
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];
}
}
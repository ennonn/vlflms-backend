<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'genre_id', 'id');
    }
}
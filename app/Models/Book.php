<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    // Permite atribuição em massa para os campos abaixo
    protected $fillable = [
        'title',
        'author',
        'description',
        'status',
        'image',
    ];

    protected static function booted()
    {
        static::deleting(function ($book) {
            $book->reviews()->delete();
        });
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

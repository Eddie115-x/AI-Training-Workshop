<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'location',
        'contact',
        'photo',
        'is_claimed',
    ];

    protected $casts = [
        'is_claimed' => 'boolean',
    ];

    public function photoUrl(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }
}

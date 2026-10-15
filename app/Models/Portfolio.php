<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'management_key_hash',
        'photo_url',
        'email',
        'phone',
        'address',
        'about',
        'education',
        'skills',
        'projects',
        'experience',
        'links',
        'template',
    ];

    protected function casts(): array
    {
        return [
            'education' => 'array',
            'skills' => 'array',
            'projects' => 'array',
            'experience' => 'array',
            'links' => 'array',
        ];
    }
}
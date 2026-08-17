<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaseStudy extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('app/assets/images/others/thumb-16.jpg');
        }

        return Str::startsWith($this->image, ['http://', 'https://', '//', '/'])
            ? $this->image
            : asset($this->image);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaseStudyImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function caseStudy()
    {
        return $this->belongsTo(CaseStudy::class);
    }

    public function getImageUrlAttribute(): string
    {
        return Str::startsWith($this->image, ['http://', 'https://', '//', '/'])
            ? $this->image
            : asset($this->image);
    }
}

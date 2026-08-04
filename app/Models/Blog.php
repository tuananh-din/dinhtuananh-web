<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = "blogs";
    public $timestamps = true;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image;

        if (empty($path)) {
            return asset('app/assets/images/others/thumb-16.jpg');
        }

        return Str::startsWith($path, ['http://', 'https://', '//', '/'])
            ? $path
            : asset($path);
    }
}

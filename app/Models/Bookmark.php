<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'url', 'profile_id'];

    // Bookmark has a many-to-one relationship with Profile
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    // Bookmark has many-to-many relationship with Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getTitleAttribute($value)
    {
        return decrypt($value);
    }

    public function getUrlAttribute($value)
    {
        return decrypt($value);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = encrypt($value);
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = encrypt($value);
    }

    public function getCoverImageAttribute()
    {
        $path = "public/images/profiles/preview-{$this->profile_id}/cover_image_{$this->id}.cwebp";
        if (Storage::exists($path)) {
            $encryptedImage = Storage::get($path);
            $decryptedImage = decrypt($encryptedImage);
            return 'data:image/webp;base64,' . base64_encode($decryptedImage);
        }
        return null;
    }
}

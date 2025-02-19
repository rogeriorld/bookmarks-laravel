<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'alt_name', 'image'];

    // One profile could have many tags

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // One profile could have many bookmarks

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function getNameAttribute($value)
    {
        return decrypt($value);
    }

    public function getAltNameAttribute($value)
    {
        return decrypt($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = encrypt($value);
    }

    public function setAltNameAttribute($value)
    {
        $this->attributes['alt_name'] = encrypt($value);
    }

    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        $imageEncripted = file_get_contents(storage_path('app/public/images/profiles/' . $value));
        $image = decrypt($imageEncripted);
        return base64_encode($image);
    }

    public function getImageName()
    {
        return $this->attributes['image'];
    }
}

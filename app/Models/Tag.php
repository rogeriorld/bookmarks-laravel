<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Add he method that permit mass assignment
    protected $fillable = ['id', 'name', 'color', 'priority'];

    // Tag has a many-to-many relationship with Bookmark
    public function bookmarks()
    {
        return $this->belongsToMany(Bookmark::class);
    }

    // Tag has a many-to-many relationship with Person
    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }

    public function getNameAttribute($value)
    {
        return decrypt($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = encrypt($value);
    }
}

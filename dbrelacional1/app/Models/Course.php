<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'available'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function coments()
    {
        return $this->morphMany(Coment::class, 'comentable');
    }


    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

}

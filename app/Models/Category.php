<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['*'];

    protected $table = 'categories';

    public function issues()
    {
        return $this->hasMany(Issue::class, 'category_id', 'id');
    }
}

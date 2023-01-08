<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $guarded = ['*'];

    protected $table = 'statuses';

    public function issues()
    {
        return $this->hasMany(Issue::class, 'status_id', 'id');
    }
}

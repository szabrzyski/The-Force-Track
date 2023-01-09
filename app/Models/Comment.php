<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ['*'];

    protected $with = ['user'];

    protected $table = 'comments';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'issue_id', 'id');
    }

}

<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $guarded = ['*'];

    protected $table = 'issues';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}

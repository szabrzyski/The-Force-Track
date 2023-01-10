<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Prunable, Notifiable, HasFactory;

    protected $guarded = ['*'];

    protected $table = 'users';

    /**
     * The attributes that should be visible for serialization.
     *
     * @var array<int, string>
     */
    protected $visible = [
        'id',
        'email',
        'admin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function prunable()
    {
        return static::whereNull('email_verified_at')->where('created_at', '<', now()->subHours(48));
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function isVerified()
    {
        return $this->email_verified_at !== null;
    }

    public function isAdmin()
    {
        return $this->admin == true;
    }

    public function verificationCodeIsValid()
    {
        $actualTime = Carbon::now();
        $accountCreationTime = Carbon::createFromDate($this->created_at);
        $timeDifference = $accountCreationTime->diffInHours($actualTime);

        return $timeDifference < 48;
    }
}

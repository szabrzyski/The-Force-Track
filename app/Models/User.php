<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Prunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
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

    public function verificationCodeIsValid()
    {
        $actualTime = Carbon::now();
        $accountCreationTime = Carbon::createFromDate($this->created_at);
        $timeDifference = $accountCreationTime->diffInHours($actualTime);

        return $timeDifference < 48;
    }

}

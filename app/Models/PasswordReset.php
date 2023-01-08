<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class PasswordReset extends Model
{
    use Prunable;

    protected $guarded = ['*'];

    protected $table = 'password_resets';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'verification_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    public $timestamps = false;

    public function verificationCodeIsValid()
    {
        $actualTime = Carbon::now();
        $resetRequestTime = Carbon::createFromDate($this->created_at);
        $timeDifference = $resetRequestTime->diffInHours($actualTime);

        return $timeDifference < 24;
    }

    public function prunable()
    {
        return static::where('created_at', '<', now()->subHours(24));
    }
}

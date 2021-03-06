<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     **/
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Return the balance.
     *
     * @return float
     */
    public function myBalance()
    {
        $inputs = 0;
        $outputs = 0;
        foreach ($this->transactions as $transaction) {
            if ($transaction->type == 'deposit') {
                $inputs += $transaction->value;
            }

            if ($transaction->type == 'withdraw') {
                $outputs += $transaction->value;
            }
        }

        return $inputs - $outputs;
    }
}

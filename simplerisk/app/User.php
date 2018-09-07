<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Risk;
class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'value';
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
     *cd
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tasks()
    {
        return $this->hasMany(Risk::class);
    }
}

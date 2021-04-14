<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait, Notifiable;

    protected $connection = 'sqlite';

    protected $primaryKey = 'provider_id';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function fileData()
    {
        return $this->hasOne(FileData::class);
    }
}

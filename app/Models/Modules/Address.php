<?php

namespace App\Models\Modules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

use Storage;

class Address extends Model
{
    use Notifiable;

    protected $table="address";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'address',"city","state","landmark","user_id","mobile_number"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}

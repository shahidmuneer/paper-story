<?php

namespace App\Models\Modules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

use Storage;

class Order extends Model
{
    use Notifiable;

    protected $table="orders";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source', 'background_design',"cost_and_cover_details","user_id","address_id","net_cost","grand_total","pdf_link","download_pdf"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // public function user()
    // {
    //     return $this->hasOne('\App\User',"sid","user_id");
    // }
    
    public function address()
    {
        return $this->hasOne('\App\Models\Modules\Address',"id","address_id");
    }
}

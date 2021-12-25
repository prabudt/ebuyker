<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'booking_load';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     * */

     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'load_id',
        'user_id',
        'booking_amount',
        'approval_flag'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];

    


    // Motters To Use Created To data Only
    public function getCreatedAtAttribute($date)
    {
        return convertUTCToLocal($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return convertUTCToLocal($date);
    }

    public function getPickupDateAttribute($date)
    {
        return convertUTCToLocal($date);
    }

    public function loads() {
        return $this->belongsTo('App\\Models\\Loads', 'load_id');
    }

    public function users() {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

}

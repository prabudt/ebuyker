<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Truck extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'truck';

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
        'user_id',
        'truck_name',
        'truck_number',        
        'truck_image',
        'location',
        'vehicle_type_id',
        'vehicle_id',
        'licene_no',
        'licene_front',
        'licene_back',
        'rc_book_number',
        'rc_image',
        'approval_flag',
        'active_flag'
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

    public function vehicleType() {
        return $this->belongsTo('App\\Models\\VehicleType', 'vehicle_type_id');
    }

    public function vehicles() {
        return $this->belongsTo('App\\Models\\Vehicles', 'vehicle_id');
    }

    public function user() {
        return $this->belongsTo('App\\Models\\user', 'user_id');
    }

    public function getIsExpiryAttribute() {
        $pickupdate = Carbon::parse($this->pickup_date)->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        return ($currentDate <= $pickupdate) ? 0 : 1;
    }

}

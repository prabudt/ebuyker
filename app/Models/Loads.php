<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Models\Booking;

class Loads extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loads';

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
        'vehicle_type_id',
        'vehicle_id',
        'load_location',
        'unload_location',
        'pickup_date',
        'material_type',
        'material_weight',
        'material_length',
        'material_width',
        'material_height',
        'amount',
        'approval_flag',
        'active_flag'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'pickup_date' => 'datetime:Y-m-d H:m:s',
    ];

    protected $appends = ['is_expiry', 'booking_status', 'limit_count'];


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

    public function vehicleType() {
        return $this->belongsTo('App\\Models\\VehicleType', 'vehicle_type_id');
    }

    public function vehicles() {
        return $this->belongsTo('App\\Models\\Vehicles', 'vehicle_id');
    }

    public function loadCreatedBy() {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function booking() {
        return $this->hasOne('App\\Models\\Booking', 'load_id');
    }

    public function getIsExpiryAttribute() {
        $pickupdate = Carbon::parse($this->pickup_date)->addDay()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        return ($currentDate <= $pickupdate) ? 0 : 1;
    }
    public function getBookingStatusAttribute() {
        $bookingData =Booking::where('load_id', $this->id)->first();
        return (!empty($bookingData)) ? ($bookingData->approval_flag == 1) ? 1: 2 : 0;
    }

    public function getLimitCountAttribute() {
        return config('constants.chat_limit');
    }

}

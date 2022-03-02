<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UsersBasedLoadBook extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_based_load_book';

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
        'booking_id ',
        'user_id',
        'approval_flag',
        'limit_count'
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

    public function BookChatData() {
        return $this->hasMany('App\\Models\\UsersBasedLoadBookChat', 'users_based_load_book_id');
    }

    public function user() {
        return $this->belongsTo('App\\Models\\user', 'user_id');
    }

    public function booking() {
        return $this->belongsTo('App\\Models\\Booking', 'booking_id');
    }

}

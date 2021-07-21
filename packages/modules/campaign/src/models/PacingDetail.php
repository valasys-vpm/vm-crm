<?php


namespace Modules\Campaign\models;


use App\Holiday;
use Illuminate\Database\Eloquent\Model;

class PacingDetail extends Model
{
    protected $guarded = array();
    public $timestamps = true;

    /*public function setDayAttribute($value)
    {
        $this->attributes['day'] = date('w', strtotime($value));
    }*/

    protected $appends = ['is_holiday'];

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'date', 'date');
    }

    public function getIsHolidayAttribute()
    {
        return isset($this->holiday) ? 1 : 0;
    }
}

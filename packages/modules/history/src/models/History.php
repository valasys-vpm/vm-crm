<?php


namespace Modules\History\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $guarded = array();
    public $timestamps = false;
    protected $appends = ['message', 'display_date'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function getMessageAttribute()
    {
        $value = json_decode($this->value);
        if(isset($value->message)) {
            return $value->message;
        } else {
            return '';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getDisplayDateAttribute()
    {
        return date('d M, Y \a\t h:i A', strtotime($this->created_at));
    }
}

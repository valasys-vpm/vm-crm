<?php


namespace Modules\User\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}

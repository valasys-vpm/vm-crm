<?php


namespace Modules\Campaign\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignUsers extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;


}

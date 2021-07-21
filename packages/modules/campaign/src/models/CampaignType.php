<?php


namespace Modules\Campaign\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignType extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;
}

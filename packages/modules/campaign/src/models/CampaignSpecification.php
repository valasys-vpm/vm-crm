<?php


namespace Modules\Campaign\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CampaignSpecification extends Model
{
    protected $guarded = array();
    public $timestamps = true;

}

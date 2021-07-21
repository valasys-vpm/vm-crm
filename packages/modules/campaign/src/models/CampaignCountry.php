<?php


namespace Modules\Campaign\models;


use App\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignCountry extends Model
{
    protected $guarded = array();
    public $timestamps = true;

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}

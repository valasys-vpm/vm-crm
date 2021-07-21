<?php


namespace Modules\Campaign\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    public function leadDetail()
    {
        return $this->hasOne(LeadDetail::class, 'campaign_id', 'id')->orderBy('created_at', 'DESC');
    }

    public function leadDetails()
    {
        return $this->hasMany(LeadDetail::class, 'campaign_id', 'id');
    }

    public function campaignType()
    {
        return $this->hasOne(CampaignType::class, 'id', 'campaign_type_id');
    }

    public function campaignFilter()
    {
        return $this->hasOne(CampaignFilter::class, 'id', 'campaign_filter_id');
    }

    public function specifications()
    {
        return $this->hasMany(CampaignSpecification::class, 'campaign_id', 'id');
    }

    public function countries()
    {
        return $this->hasMany(CampaignCountry::class, 'campaign_id', 'id');
    }
}

<?php


namespace App\Repository\Campaign\CampaignHistory;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\models\CampaignHistory;
use Modules\Permission\models\Permission;

class CampaignHistoryRepository implements CampaignHistoryInterface
{
    private $campaignHistory;

    public function __construct(CampaignHistory $campaignHistory)
    {
        $this->campaignHistory = $campaignHistory;
    }

    public function getAll($filters = array())
    {
        // TODO: Implement getAll() method.
    }

    public function getCampaignHistory($campaign_id, $filters = [])
    {
        $limit = 10;
        $query = $this->campaignHistory->whereNotNull('id');
        if(isset($filters['order_by']) && $filters['order_by']) {
            $query->orderBy($filters['order_by']);
        }

        if(isset($filters['order_by_desc']) && $filters['order_by_desc']) {
            $query->orderBy($filters['order_by_desc'], 'DESC');
        }

        if(!empty($campaign_id)) {
            $query->whereCampaignId($campaign_id);
        }

        $query->with('user.userDetail');
        if(isset($filters['skip']) && !empty($filters['skip'])) {
            $query->limit($limit)->skip($filters['skip'] * $limit);
        } else {
            $query->limit($limit);
        }
        return $query->get();
    }

    public function store($attributes)
    {
        try {
            //dd($attributes);
            DB::beginTransaction();
            $campaignHistory = new CampaignHistory();
            $campaignHistory->campaign_id = $attributes['campaign_id'];
            if(isset($attributes['lead_detail_id'])) {
                $campaignHistory->lead_detail_id = $attributes['lead_detail_id'];
            }
            $campaignHistory->action = $attributes['action'];
            $campaignHistory->user_id = Auth::id();
            $campaignHistory->save();
            if($campaignHistory->id) {
                DB::commit();
            } else {
                //throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
        }
    }


}

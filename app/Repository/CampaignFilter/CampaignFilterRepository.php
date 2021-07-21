<?php


namespace App\Repository\CampaignFilter;


use App\Helper\Helper;
use App\Repository\History\HistoryRepository;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\enum\CampaignFilterStatus;
use Modules\Campaign\models\CampaignFilter;

class CampaignFilterRepository implements CampaignFilterInterface
{
    private $campaignFilter;
    private $historyRepository;

    public function __construct(CampaignFilter $campaignFilter, HistoryRepository $historyRepository)
    {
        $this->campaignFilter = $campaignFilter;
        $this->historyRepository = $historyRepository;
    }

    public function getAll($filters = array())
    {
        $query = $this->campaignFilter->whereNotNull('id');
        if(isset($filters['order_by']) && $filters['order_by']) {
            $query->orderBy($filters['order_by']);
        }
        if(isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        return $query->get();
    }

    public function find($id)
    {
        return $this->campaignFilter->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign_filter = new CampaignFilter();
            $campaign_filter->name = strtoupper($attributes['name']);
            $campaign_filter->full_name = ucfirst($attributes['full_name']);
            $campaign_filter->status = $attributes['status'];
            $campaign_filter->save();
            if($campaign_filter->id) {
                //Save History
                $this->historyRepository->store(array('route' => 'campaign_filter.create', 'action' => 'Created new campaign filter', 'value' => array('id' => $campaign_filter->id, 'message' => Helper::getCreatedMessage('Campaign Filter', $campaign_filter->name))));
                //--Save History
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign filter created successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign_filter = $this->find($id);
            $campaign_filter_copy = $campaign_filter->toArray();
            $campaign_filter->name = strtoupper($attributes['name']);
            $campaign_filter->full_name = ucfirst($attributes['full_name']);
            $campaign_filter->status = $attributes['status'];
            $campaign_filter->save();
            if($campaign_filter->id) {
                //Save History
                $updatedData = Helper::getUpdatedData($campaign_filter->getChanges(), $campaign_filter_copy);
                $message = 'Updated Campaign Filter - '.$campaign_filter->name.'<br>Updated Fields are:';
                foreach ($updatedData as $key => $value) {
                    if($key == 'status') {
                        $message .= Helper::getUpdatedMessage($key, CampaignFilterStatus::CAMPAIGN_FILTER_STATUS[$value['new']], CampaignFilterStatus::CAMPAIGN_FILTER_STATUS[$value['old']]);
                    } else {
                        $message .= Helper::getUpdatedMessage($key, $value['new'], $value['old']);
                    }
                }
                $this->historyRepository->store(array('route' => 'campaign_filter.edit', 'action' => 'Updated campaign filter', 'value' => array('id' => $campaign_filter->id, 'data' => $updatedData, 'message' => $message)));
                //--Save History
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign filter updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id)
    {
        $campaign_filter = $this->find($id);
        $name = $campaign_filter->name;
        if($campaign_filter->delete()) {
            //Save History
            $this->historyRepository->store(array('route' => 'campaign_filter.destroy', 'action' => 'Deleted campaign filter', 'value' => array('id' => $id, 'message' => Helper::getDeletedMessage('Campaign Filter', $name))));
            //--Save History
            return $response = array('status' => TRUE, 'message' => 'Campaign filter deleted successfully');
        } else {
            return $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
    }


}

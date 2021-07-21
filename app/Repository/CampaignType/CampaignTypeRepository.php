<?php


namespace App\Repository\CampaignType;


use App\Helper\Helper;
use App\Repository\History\HistoryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\enum\CampaignTypeStatus;
use Modules\Campaign\models\CampaignType;

class CampaignTypeRepository implements CampaignTypeInterface
{
    private $campaignType;
    private $historyRepository;

    public function __construct(CampaignType $campaignType, HistoryRepository $historyRepository)
    {
        $this->campaignType = $campaignType;
        $this->historyRepository = $historyRepository;
    }

    public function getAll($filters = array())
    {
        $query = $this->campaignType->whereNotNull('id');
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
        return $this->campaignType->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign_type = new CampaignType();
            $campaign_type->name = strtoupper($attributes['name']);
            $campaign_type->full_name = ucfirst($attributes['full_name']);
            $campaign_type->status = $attributes['status'];
            $campaign_type->save();
            if($campaign_type->id) {
                //Save History
                $this->historyRepository->store(array('route' => 'campaign_type.create', 'action' => 'Created new campaign type', 'value' => array('id' => $campaign_type->id, 'message' => Helper::getCreatedMessage('Campaign Type', $campaign_type->name))));
                //--Save History
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign Type created successfully');
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
            $campaignType = $this->find($id);
            $campaignTypeCopy = $campaignType->toArray();
            $campaignType->name = strtoupper($attributes['name']);
            $campaignType->full_name = ucfirst($attributes['full_name']);
            $campaignType->status = $attributes['status'];
            $campaignType->save();
            if($campaignType->id) {
                //Save History
                $updatedData = Helper::getUpdatedData($campaignType->getChanges(), $campaignTypeCopy);
                $message = 'Updated Campaign Type - '.$campaignType->name.'<br>Updated Fields are:';
                foreach ($updatedData as $key => $value) {
                    if($key == 'status') {
                        $message .= Helper::getUpdatedMessage($key, CampaignTypeStatus::CAMPAIGN_TYPE_STATUS[$value['new']], CampaignTypeStatus::CAMPAIGN_TYPE_STATUS[$value['old']]);
                    } else {
                        $message .= Helper::getUpdatedMessage($key, $value['new'], $value['old']);
                    }
                }
                $this->historyRepository->store(array('route' => 'campaign_type.edit', 'action' => 'Updated campaign type', 'value' => array('id' => $campaignType->id, 'data' => $updatedData, 'message' => $message)));
                //--Save History
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign Type updated successfully');
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
        $campaignType = $this->find($id);
        $campaignTypeName = $campaignType->name;
        if($campaignType->delete()) {
            //Save History
            $this->historyRepository->store(array('route' => 'campaign_type.destroy', 'action' => 'Deleted campaign type', 'value' => array('id' => $id, 'message' => Helper::getDeletedMessage('Campaign Type', $campaignTypeName))));
            //--Save History
            return $response = array('status' => TRUE, 'message' => 'Campaign Type deleted successfully');
        } else {
            return $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
    }


}

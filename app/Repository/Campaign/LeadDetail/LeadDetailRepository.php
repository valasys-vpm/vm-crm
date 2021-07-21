<?php


namespace App\Repository\Campaign\LeadDetail;


use App\Helper\Helper;
use App\Repository\Campaign\CampaignHistory\CampaignHistoryRepository;
use App\Repository\History\HistoryRepository;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\enum\CampaignStatus;
use Modules\Campaign\models\Campaign;
use Modules\Campaign\models\LeadDetail;
use Modules\Campaign\models\PacingDetail;

class LeadDetailRepository implements LeadDetailInterface
{
    private $leadDetail;
    private $campaignHistoryRepository;
    private $historyRepository;
    private $campaign;

    public function __construct(
        LeadDetail $leadDetail,
        Campaign $campaign,
        CampaignHistoryRepository $campaignHistoryRepository,
        HistoryRepository $historyRepository
    )
    {
        $this->leadDetail = $leadDetail;
        $this->campaignHistoryRepository = $campaignHistoryRepository;
        $this->historyRepository = $historyRepository;
        $this->campaign = $campaign;
    }

    public function getAll($filters = [])
    {
        // TODO: Implement getAll() method.
    }

    public function find($id)
    {
        return $this->leadDetail->with([
            'pacingDetails'
        ])->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            //Lead Details
            $leadDetail = new LeadDetail();
            $leadDetail->campaign_id = $attributes['id'];
            $leadDetail->start_date = $attributes['start_date'];
            $leadDetail->end_date = $attributes['end_date'];
            $leadDetail->allocation = $attributes['allocation'];
            $leadDetail->campaign_status = $attributes['campaign_status'];
            $leadDetail->pacing = $attributes['pacing'];
            $leadDetail->save();
            if($leadDetail->id) {
                //Pacing Details
                $insertPacingDetails = array();

                foreach ($attributes['sub-allocation'] as $date => $sub_allocation) {
                    array_push($insertPacingDetails, [
                        'campaign_id' => $attributes['id'],
                        'lead_detail_id' => $leadDetail->id,
                        'sub_allocation' => $sub_allocation,
                        'date' => $date,
                        'day' => date('w', strtotime($date))
                    ]);
                }
                PacingDetail::insert($insertPacingDetails);
                //--Pacing Details
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'New lead created successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
            //--Lead Details

            //Save History
            $campaign = $this->campaign->findOrFail($attributes['id']);
            $historyAttributes = array(
                'route' => 'campaign.create_new_lead',
                'action' => 'Added new lead for Campaign: '.$campaign->campaign_id,
                'value' => array('id' => $campaign->id, 'message' => 'Added new lead with following details: <br><b>Start Date: </b>'.date('d-M-Y', strtotime($attributes['start_date'])).'<br><b>End Date: </b>'.date('d-M-Y', strtotime($attributes['end_date'])))
            );
            $this->historyRepository->store($historyAttributes);
            $this->campaignHistoryRepository->store(array('campaign_id' => $attributes['id'], 'action' => 'Added new lead with following details: <br><b>Start Date: </b>'.date('d-M-Y', strtotime($attributes['start_date'])).'<br><b>End Date: </b>'.date('d-M-Y', strtotime($attributes['end_date']))));
            //--Save History
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes = [])
    {
        //dd($id, $attributes);
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            //Lead Details
            $leadDetail = $this->find($id);
            $leadDetailCopy = $leadDetail->toArray();
            $leadDetail->start_date = $attributes['start_date'];
            $leadDetail->end_date = $attributes['end_date'];
            $leadDetail->allocation = $attributes['allocation'];
            $leadDetail->campaign_status = $attributes['campaign_status'];
            $leadDetail->deliver_count = $attributes['deliver_count'];

            if($attributes['campaign_status'] == CampaignStatus::SHORTFALL && isset($attributes['shortfall_count'])) {
                $leadDetail->shortfall_count = $attributes['shortfall_count'];
            } else {
                $leadDetail->shortfall_count = NULL;
            }

            $leadDetail->save();
            if($leadDetail->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Lead details updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
            $getChanges = $leadDetail->getChanges();

            if(isset($getChanges['start_date'])) {
                //$resultPacingDetails = PacingDetail::where('date', '<', $getChanges['start_date'])->get();
                $resultPacingDetails = PacingDetail::where('date', '<', $getChanges['start_date'])->delete();
            }

            if(isset($getChanges['end_date'])) {
                //$resultPacingDetails = PacingDetail::where('date', '>', $getChanges['end_date'])->get();
                $resultPacingDetails = PacingDetail::where('date', '>', $getChanges['end_date'])->delete();
            }
            //--Lead Details



            //Save History
            $updatedData = Helper::getUpdatedData($getChanges, $leadDetailCopy);
            $message = '';
            //dd($updatedData);
            foreach ($updatedData as $key => $value) {
                switch ($key) {
                    case 'campaign_status':
                        $message .= Helper::getUpdatedMessage($key, CampaignStatus::CAMPAIGN_STATUS[$value['new']], CampaignStatus::CAMPAIGN_STATUS[$value['old']]);
                        break;
                    case 'start_date':
                    case 'end_date':
                        $message .= Helper::getUpdatedMessage($key, date('d-M-Y', strtotime($value['new'])), date('d-M-Y', strtotime($value['old'])));
                        break;
                    default:
                        $message .= Helper::getUpdatedMessage($key, $value['new'], $value['old']);
                }
            }

            if(!empty($updatedData)) {
                $campaign = $this->campaign->findOrFail($leadDetail->campaign_id);
                $historyAttributes = array(
                    'route' => 'campaign.create_new_lead',
                    'action' => 'Updated lead details for Campaign: '.$campaign->campaign_id,
                    'value' => array('id' => $campaign->id, 'message' => 'Updated fields are:'.$message)
                );
                $this->historyRepository->store($historyAttributes);
                $this->campaignHistoryRepository->store(array('campaign_id' => $leadDetail->campaign_id, 'lead_detail_id' => $leadDetail->id, 'action' => 'Updated lead details <br> Updated fields are:'.$message));
            }
            //--Save History
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }


}

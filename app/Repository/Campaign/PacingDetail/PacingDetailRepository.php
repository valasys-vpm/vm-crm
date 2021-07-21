<?php


namespace App\Repository\Campaign\PacingDetail;


use App\Helper\Helper;
use App\Repository\Campaign\CampaignHistory\CampaignHistoryRepository;
use App\Repository\History\HistoryRepository;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\models\Campaign;
use Modules\Campaign\models\PacingDetail;

class PacingDetailRepository implements PacingDetailInterface
{
    private $pacingDetail;
    private $campaignHistoryRepository;
    private $historyRepository;

    public function __construct(
        PacingDetail $pacingDetail,
        CampaignHistoryRepository $campaignHistoryRepository,
        HistoryRepository $historyRepository
    )
    {
        $this->pacingDetail = $pacingDetail;
        $this->campaignHistoryRepository = $campaignHistoryRepository;
        $this->historyRepository = $historyRepository;
    }

    public function getAll($filters = [])
    {
        // TODO: Implement getAll() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function store($attributes)
    {
        // TODO: Implement store() method.
    }

    public function update($lead_id, $attributes = [])
    {
        //dd($lead_id, $attributes);
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            //Pacing Details

            $resultPacingDetails = PacingDetail::where('lead_detail_id', $lead_id)->get()->pluck('sub_allocation', 'date');

            PacingDetail::where('lead_detail_id', $lead_id)->delete();

            $insertPacingDetails = array();

            foreach ($attributes['sub-allocation'] as $date => $sub_allocation) {
                array_push($insertPacingDetails, [
                    'campaign_id' => $attributes['campaign_id'],
                    'lead_detail_id' => $lead_id,
                    'sub_allocation' => $sub_allocation,
                    'date' => $date,
                    'day' => date('w', strtotime($date))
                ]);
            }
            if(PacingDetail::insert($insertPacingDetails)) {
                $resultUpdatedPacingDetails = PacingDetail::where('lead_detail_id', $lead_id)->get()->pluck('sub_allocation', 'date');

                //Save History
                if(count(array_diff_assoc($resultPacingDetails->toArray(), $resultUpdatedPacingDetails->toArray()))) {
                    $oldArray = $resultPacingDetails->toArray();
                    $newArray = $resultUpdatedPacingDetails->toArray();
                    $data = array_merge($oldArray, $newArray);
                    $message = '';
                    foreach ($data as $key => $value) {
                        $message .= '<br>- '.date('d-M-Y', strtotime($key)).': from <b>'.(empty($oldArray[$key]) ? 0 : $oldArray[$key]).'</b> to <b>'.(empty($newArray[$key]) ? 0 : $newArray[$key]).'</b>';
                    }
                    $campaign = Campaign::findOrFail($attributes['campaign_id']);
                    $historyAttributes = array(
                        'route' => 'campaign.update_sub_allocations',
                        'action' => 'Updated Sub-Allocations In Campaign: '.$campaign->campaign_id,
                        'value' => array('id' => $campaign->id, 'message' => 'Updated sub-allocations are:'.$message)
                    );
                    $this->historyRepository->store($historyAttributes);
                    $this->campaignHistoryRepository->store(array('campaign_id' => $attributes['campaign_id'], 'lead_detail_id' => $lead_id, 'action' => 'Updated Sub-Allocations <br> Updated sub-allocations are:'.$message));
                }
                //--Save History

                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Sub allocations updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
            //--Pacing Details



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

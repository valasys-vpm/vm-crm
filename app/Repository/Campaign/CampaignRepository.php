<?php


namespace App\Repository\Campaign;


use App\Helper\Helper;
use App\Holiday;
use App\Jobs\SendMail;
use App\Repository\Campaign\CampaignHistory\CampaignHistoryRepository;
use App\Repository\History\HistoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Campaign\models\Campaign;
use Modules\Campaign\models\CampaignCountry;
use Modules\Campaign\models\CampaignFilter;
use Modules\Campaign\models\CampaignSpecification;
use Modules\Campaign\models\CampaignType;
use Modules\Campaign\models\LeadDetail;
use Modules\Campaign\models\PacingDetail;

class CampaignRepository implements CampaignInterface
{
    private $campaign;
    private $campaignCountry;
    private $campaignSpecification;
    private $leadDetail;
    private $pacingDetail;
    private $holiday;
    private $historyRepository;
    private $campaignHistoryRepository;

    public function __construct(
        Campaign $campaign,
        CampaignCountry $campaignCountry,
        CampaignSpecification $campaignSpecification,
        LeadDetail $leadDetail,
        PacingDetail $pacingDetail,
        Holiday $holiday,
        HistoryRepository $historyRepository,
        CampaignHistoryRepository $campaignHistoryRepository
    )
    {
        $this->campaign = $campaign;
        $this->campaignCountry = $campaignCountry;
        $this->campaignSpecification = $campaignSpecification;
        $this->leadDetail = $leadDetail;
        $this->pacingDetail = $pacingDetail;
        $this->holiday = $holiday;
        $this->historyRepository = $historyRepository;
        $this->campaignHistoryRepository = $campaignHistoryRepository;
    }

    public function getAll($filters = [])
    {
        $query = $this->campaign->whereNotNull('id');
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
        return $this->campaign->with([
            'leadDetails.pacingDetails',
            'campaignType',
            'campaignFilter',
            'specifications',
            'countries.country.region'
        ])->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign = new Campaign();
            $lastRecord = Campaign::latest('id')->first();
            if(isset($lastRecord)) {
                $lastId = str_pad($lastRecord->id + 1,6,"0",STR_PAD_LEFT);
            } else {
                $lastId = str_pad('1',6,"0",STR_PAD_LEFT);
            }
            $resultCampaignType = CampaignType::find($attributes['campaign_type_id']);
            $campaign->name = $attributes['name'];
            $campaign->campaign_id = Helper::getSiteSetting('Campaign Abbreviation').$resultCampaignType->name.'-'.$lastId;
            $campaign->v_mail_campaign_id = $attributes['v_mail_campaign_id'];
            $campaign->campaign_type_id = $attributes['campaign_type_id'];
            $campaign->campaign_filter_id = $attributes['campaign_filter_id'];
            $campaign->note = $attributes['note'];
            $campaign->save();
            if($campaign->id) {

                //Campaign Countries
                $insertCampaignCountries = array();
                foreach ($attributes['country_id'] as $country) {
                    array_push($insertCampaignCountries, ['campaign_id' => $campaign->id, 'country_id' => $country]);
                }
                CampaignCountry::insert($insertCampaignCountries);
                //--Campaign Countries

                //Campaign Specifications

                $insertCampaignSpecifications = array();
                if(isset($attributes['specifications']) && !empty($attributes['specifications'])) {
                    foreach ($attributes['specifications'] as $file) {
                        $extension = $file->getClientOriginalExtension();
                        //$filename  = $campaign->campaign_id.'-' . str_shuffle(time()) . '.' . $extension;
                        $filename  = $file->getClientOriginalName();
                        $path = 'public/campaigns/'.$campaign->campaign_id;
                        $result  = $file->storeAs($path, $filename);
                        array_push($insertCampaignSpecifications, ['campaign_id' => $campaign->id, 'file_name' => $filename]);
                    }
                    CampaignSpecification::insert($insertCampaignSpecifications);
                }

                //--Campaign Specifications

                //Lead Details
                $leadDetail = new LeadDetail();
                $leadDetail->campaign_id = $campaign->id;
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
                            'campaign_id' => $campaign->id,
                            'lead_detail_id' => $leadDetail->id,
                            'sub_allocation' => $sub_allocation,
                            'date' => $date,
                            'day' => date('w', strtotime($date))
                        ]);
                    }
                    PacingDetail::insert($insertPacingDetails);
                    //--Pacing Details
                    DB::commit();
                    $response = array('status' => TRUE, 'message' => 'Campaign created successfully');
                } else {
                    //throw new \Exception('Something went wrong, please try again.', 1);
                }
                //--Lead Details

                //Save History
                $this->historyRepository->store(array('route' => 'campaign.create', 'action' => 'Created new campaign', 'value' => array('id' => $campaign->id, 'message' => Helper::getCreatedMessage('Campaign', $campaign->name))));
                $this->campaignHistoryRepository->store(array('campaign_id' => $campaign->id, 'action' => 'Created new campaign: '.$campaign->name));
                //--Save History
                //Send Mail
                dispatch(new SendMail([ 'email' => env('SYSTEM_MAIL_ADDRESS'),
                    'subject' => 'Created new campaign',
                    'content' => 'Created new campaign: '.$campaign->name
                ]));
                //--Send Mail
            } else {
                //throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes = [])
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign = $this->find($id);
            $campaignCopy = $campaign->toArray();
            $campaign->name = $attributes['name'];
            $campaign->v_mail_campaign_id = $attributes['v_mail_campaign_id'];
            $campaign->campaign_type_id = $attributes['campaign_type_id'];
            $campaign->campaign_filter_id = $attributes['campaign_filter_id'];
            $campaign->note = $attributes['note'];
            $campaign->save();
            if($campaign->id) {
                //Campaign Countries

                $insertCampaignCountries = array();
                foreach ($attributes['country_id'] as $country) {
                    array_push($insertCampaignCountries, ['campaign_id' => $campaign->id, 'country_id' => $country]);
                }
                $resultCountryList = CampaignCountry::where('campaign_id', $campaign->id)->with('country')->get()->pluck('country.name');
                CampaignCountry::where('campaign_id', $campaign->id)->delete();

                CampaignCountry::insert($insertCampaignCountries);
                $resultUpdatedCountryList = CampaignCountry::where('campaign_id', $campaign->id)->with('country')->get()->pluck('country.name');
                if($resultUpdatedCountryList->count() >=  $resultCountryList->count()) {
                    $resultCountryDiff = $resultUpdatedCountryList->diff($resultCountryList);
                } else {
                    $resultCountryDiff = $resultCountryList->diff($resultUpdatedCountryList);
                }
                //--Campaign Countries

                $response = array('status' => TRUE, 'message' => 'Campaign details updated successfully');
                //Save History
                $updatedCampaignData = Helper::getUpdatedData($campaign->getChanges(), $campaignCopy);
                if($resultCountryDiff->count() > 0) {
                    $updatedCampaignData['countries']['new'] = implode(', ',$resultUpdatedCountryList->toArray());
                    $updatedCampaignData['countries']['old'] = implode(', ',$resultCountryList->toArray()   );
                }
                //dd($updatedCampaignData);
                $message = '';
                foreach ($updatedCampaignData as $key => $value) {
                    switch ($key) {
                        case 'campaign_type_id':
                            $new = CampaignType::findOrFail($value['new']);
                            $old = CampaignType::findOrFail($value['old']);
                            $message .= Helper::getUpdatedMessage($key, $new->name, $old->name);
                            break;
                        case 'campaign_filter_id':
                            $new = CampaignFilter::findOrFail($value['new']);
                            $old = CampaignFilter::findOrFail($value['old']);
                            $message .= Helper::getUpdatedMessage($key, $new->name, $old->name);
                            break;
                        default:
                            $message .= Helper::getUpdatedMessage($key, $value['new'], $value['old']);
                    }
                }
                $historyAttributes = array(
                    'route' => 'campaign.edit',
                    'action' => 'Updated campaign details',
                    'value' => array('id' => $campaign->id, 'message' => 'Updated fields are:'.$message)
                );
                $this->historyRepository->store($historyAttributes);
                $this->campaignHistoryRepository->store(array('campaign_id' => $campaign->id, 'action' => 'Updated campaign details <br> Updated fields are:'.$message));
                //--Save History
                DB::commit();
            } else {
                //throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function updateSpecification($id, $attributes = [])
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign = $this->find($id);
            //Campaign Specifications
            $insertCampaignSpecifications = array();
            $fileNames = [];
            $path = 'public/campaigns/'.$campaign->campaign_id;
            foreach ($attributes['specifications'] as $file) {

                $extension = $file->getClientOriginalExtension();
                //$filename  = $campaign->campaign_id.'-' . str_shuffle(time()) . '.' . $extension;
                $filename  = $file->getClientOriginalName();
                $result  = $file->storeAs($path, $filename);
                array_push($insertCampaignSpecifications, ['campaign_id' => $campaign->id, 'file_name' => $filename]);
                array_push($fileNames, $filename);
            }
            //dd($insertCampaignSpecifications, $fileNames);
            //--Campaign Specifications

            if(CampaignSpecification::insert($insertCampaignSpecifications)) {
                $lastInserted = CampaignSpecification::whereIn('file_name', $fileNames)->get();
                $historyAttributes = array(
                    'route' => 'campaign.attach_specification',
                    'action' => 'Added specifications for Campaign: '.$campaign->campaign_id,
                    'value' => array('id' => $campaign->id, 'message' => 'Added specifications:'.implode(', ', $fileNames))
                );
                $this->historyRepository->store($historyAttributes);
                $this->campaignHistoryRepository->store(array('campaign_id' => $campaign->id, 'action' => 'Added specifications: '.implode(', ', $fileNames)));
                //--Save History
                $response = array('status' => TRUE, 'data' => $lastInserted);
                DB::commit();

                //Send Mail
                dispatch(new SendMail([ 'email' => env('SYSTEM_MAIL_ADDRESS'),
                    'subject' => 'Added specifications',
                    'content' => 'Added specifications:'.implode(', ', $fileNames)
                ]));
                //--Send Mail

            } else {
                //throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function removeSpecification($id, $attributes = [])
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $specification = CampaignSpecification::findOrFail($id);
            $campaign = Campaign::findOrFail($specification->campaign_id);
            $file_name = $specification->file_name;
            $file_path = 'public/campaigns/'.$campaign->campaign_id.'/'.$file_name;
            if(Storage::exists($file_path)) {
                Storage::delete($file_path);
                $specification->delete();
                $historyAttributes = array(
                    'route' => 'campaign.remove_specification',
                    'action' => 'Removed specification from Campaign: '.$campaign->campaign_id,
                    'value' => array('id' => $campaign->id, 'message' => 'Removed specification: '.$file_name)
                );
                $this->historyRepository->store($historyAttributes);
                $this->campaignHistoryRepository->store(array('campaign_id' => $campaign->id, 'action' => 'Removed specification: '.$file_name));
                //--Save History
                $response = array('status' => TRUE);
                DB::commit();

                //Send Mail
                dispatch(new SendMail([ 'email' => env('SYSTEM_MAIL_ADDRESS'),
                    'subject' => 'Removed specification',
                    'content' => 'Removed specification: '.$file_name
                ]));
                //--Send Mail
            } else {
                //throw new \Exception('Something went wrong, please try again.', 1);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }


}

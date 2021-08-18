<?php


namespace App\Repository\Campaign;


use App\Country;
use App\Helper\Helper;
use App\Holiday;
use App\Jobs\SendMail;
use App\Repository\Campaign\CampaignHistory\CampaignHistoryRepository;
use App\Repository\History\HistoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Campaign\enum\CampaignStatus;
use Modules\Campaign\enum\PacingType;
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
                if(!empty($attributes['country_id']) && count($attributes['country_id'])) {
                    $insertCampaignCountries = array();
                    foreach ($attributes['country_id'] as $country) {
                        array_push($insertCampaignCountries, ['campaign_id' => $campaign->id, 'country_id' => $country]);
                    }
                    CampaignCountry::insert($insertCampaignCountries);
                }
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

                if(isset($attributes['deliver_count']) && $attributes['deliver_count'] > 0) {
                    $leadDetail->deliver_count = $attributes['deliver_count'];
                }

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
                    $response = array('status' => TRUE, 'message' => 'Campaign created successfully', 'campaign_id' => $campaign->campaign_id, 'id' => $campaign->id);
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
            dd($exception->getMessage());
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

    public function validateCampaignData($data = [])
    {
        $validatedData = array();
        $errorMessage = array();
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        $invalidCells = array();
        try {
            //Validate Campaign Name $data[0]
            if(!empty(trim($data[0]))) {
                $campaign = Campaign::whereName(trim($data[0]))->count();
                if($campaign == 0) {
                    $validatedData['name'] = $data[0];
                } else {
                    $errorMessage['Campaign Name'] = 'Campaign name already exists';
                    $invalidCells[0] = 'Invalid';
                }
            } else {
                $errorMessage['Campaign Name'] = 'Enter valid campaign name';
                $invalidCells[0] = 'Invalid';
            }

            //Validate V-Mail Campaign ID $data[1]
            $validatedData['v_mail_campaign_id'] = $data[1];

            //Validate Campaign Type $data[2]
            if(!empty(trim($data[2]))) {
                $campaignType = CampaignType::whereName(trim($data[2]))->first();
                if(!empty($campaignType)) {
                    $validatedData['campaign_type_id'] = $campaignType->id;
                } else {
                    $errorMessage['Campaign Type'] = 'Enter valid campaign type';
                    $invalidCells[2] = 'Invalid';
                }
            } else {
                $errorMessage['Campaign Type'] = 'Enter valid campaign type';
                $invalidCells[2] = 'Invalid';
            }


            //Validate Campaign Filter $data[3]
            if(!empty(trim($data[3]))) {
                $campaignFilter = CampaignFilter::whereName(trim($data[3]))->first();
                if(!empty($campaignFilter)) {
                    $validatedData['campaign_filter_id'] = $campaignFilter->id;
                } else {
                    $errorMessage['Campaign Filter'] = 'Enter valid campaign filter';
                    $invalidCells[3] = 'Invalid';
                }
            } else {
                $errorMessage['Campaign Filter'] = 'Enter valid campaign filter';
                $invalidCells[3] = 'Invalid';
            }

            //Validate Country(s) $data[4]
            if(!empty(trim($data[4]))) {
                $countries = explode(',', strtolower(trim($data[4])));
                $country = Country::select('id')->whereIn('name', $countries)->get();
                if(($country->count() == count($countries))) {
                    $country_id = array();
                    foreach ($country as $item) {
                        array_push($country_id, $item->id);
                    }
                    $validatedData['country_id'] = $country_id;
                } else {
                    $errorMessage['Countries'] = 'Enter valid countries';
                    $invalidCells[4] = 'Invalid';
                }
            } else {
                $validatedData['country_id'] = [];
                //$errorMessage['Countries'] = 'Enter valid countries';
                //$invalidCells[4] = 'Invalid';
            }

            //Validate Start Date $data[5]
            if(!empty(trim($data[5]))) {
                $start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[5]);
                $start_date = date_format($start_date, 'Y-m-d');
                if($start_date != '1970-01-01') {
                    $validatedData['start_date'] = $start_date;
                } else {
                    $errorMessage['Start Date'] = 'Enter valid start date';
                    $invalidCells[5] = 'Invalid';
                }
            } else {
                $errorMessage['Start Date'] = 'Enter valid start date';
                $invalidCells[5] = 'Invalid';
            }

            //Validate End Date $data[6]
            if(!empty(trim($data[6]))) {
                $end_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[6]);
                $end_date = date_format($end_date, 'Y-m-d');
                if($end_date != '1970-01-01') {
                    $validatedData['end_date'] = $end_date;
                } else {
                    $errorMessage['End Date'] = 'Enter valid end date';
                    $invalidCells[6] = 'Invalid';
                }
            } else {
                $errorMessage['End Date'] = 'Enter valid end date';
                $invalidCells[6] = 'Invalid';
            }

            //Check Start Date & End Date
            if($start_date > $end_date) {
                $errorMessage['Start Date & End Date'] = 'Enter valid start date & end date';
            }

            //Validate Allocation $data[7]
            if(!empty(trim($data[7]))) {
                $allocation = trim($data[7]);
                if(is_numeric($allocation) && $allocation > 0) {
                    $validatedData['allocation'] = $allocation;
                } else {
                    $errorMessage['Allocation'] = 'Enter valid allocation';
                    $invalidCells[7] = 'Invalid';
                }
            } else {
                $errorMessage['Allocation'] = 'Enter valid allocation';
                $invalidCells[7] = 'Invalid';
            }

            //Validate Status $data[8]
            if(!empty(trim($data[8]))) {
                $campaignStatus = CampaignStatus::CAMPAIGN_STATUS;
                if(in_array(ucfirst(trim($data[8])), $campaignStatus)) {
                    $validatedData['campaign_status'] = array_search(ucfirst(trim($data[8])),$campaignStatus);
                } else {
                    $errorMessage['Status'] = 'Enter valid status';
                    $invalidCells[8] = 'Invalid';
                }
            } else {
                $errorMessage['Status'] = 'Enter valid status';
                $invalidCells[8] = 'Invalid';
            }

            //Validate Pacing $data[9]
            if(!empty(trim($data[9]))) {
                $pacings = PacingType::PACING_TYPE;
                if(in_array(ucfirst(trim($data[9])), $pacings)) {
                    $validatedData['pacing'] = ucfirst(trim($data[9]));
                } else {
                    $errorMessage['Pacing'] = 'Enter valid pacing';
                    $invalidCells[9] = 'Invalid';
                }
            } else {
                $errorMessage['Pacing'] = 'Enter valid pacing';
                $invalidCells[9] = 'Invalid';
            }

            //Validate Delivery Count $data[10]
            if(!empty(trim($data[10]))) {
                $deliver_count = trim($data[10]);
                if(is_numeric($deliver_count) && $deliver_count > 0) {
                    $validatedData['deliver_count'] = $deliver_count;
                } else {
                    $errorMessage['Delivery Count'] = 'Enter valid delivery count';
                    $invalidCells[10] = 'Invalid';
                }
            } else {
                $validatedData['deliver_count'] = 0;
                //$errorMessage['Allocation'] = 'Enter valid allocation';
                //$invalidCells[10] = 'Invalid';
            }

            //Validate Sub-Allocation
            $validatedData['note'] = '';
            $validatedData['sub-allocation'] = [];

            if(empty($errorMessage)) {
                $response = array('status' => TRUE, 'validatedData' => $validatedData);
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }

        } catch (\Exception $exception) {
            //dd($exception->getMessage());
            $response = array(
                'status' => FALSE,
                'errorMessage' => $errorMessage,
                'invalidCells' => $invalidCells
                );
        }

        return $response;


    }

}

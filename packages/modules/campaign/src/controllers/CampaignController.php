<?php


namespace Modules\Campaign\controllers;

use App\Country;
use App\Exports\ArrayToExcel;
use App\Exports\CampaignExport;
use App\Holiday;
use App\Http\Controllers\Controller;
use App\Region;
use App\Repository\Campaign\CampaignHistory\CampaignHistoryRepository;
use App\Repository\Campaign\CampaignRepository;
use App\Repository\Campaign\LeadDetail\LeadDetailRepository;
use App\Repository\Campaign\PacingDetail\PacingDetailRepository;
use App\Repository\CampaignFilter\CampaignFilterRepository;
use App\Repository\CampaignType\CampaignTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Campaign\models\Campaign;
use Modules\Campaign\models\CampaignSpecification;
use Modules\Campaign\models\PacingDetail;

use Excel;
use Zip;

class CampaignController extends Controller
{
    private $data;
    private $campaignRepository;
    private $campaignTypeRepository;
    private $campaignFilterRepository;
    private $leadDetailRepository;
    private $pacingDetailRepository;
    private $campaignHistoryRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        CampaignTypeRepository $campaignTypeRepository,
        CampaignFilterRepository $campaignFilterRepository,
        LeadDetailRepository $leadDetailRepository,
        PacingDetailRepository $pacingDetailRepository,
        CampaignHistoryRepository $campaignHistoryRepository
    )
    {
        $this->data = array();
        $this->campaignRepository = $campaignRepository;
        $this->campaignTypeRepository = $campaignTypeRepository;
        $this->campaignFilterRepository = $campaignFilterRepository;
        $this->leadDetailRepository = $leadDetailRepository;
        $this->pacingDetailRepository = $pacingDetailRepository;
        $this->campaignHistoryRepository = $campaignHistoryRepository;
    }

    public function index()
    {
        $this->data['resultCampaigns'] = $this->campaignRepository->getAll();
        $this->data['resultCountries'] = Country::get();
        $this->data['resultRegions'] = Region::get();
        $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->getAll(array('status' => '1', 'order_by' => 'name'));
        $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->getAll(array('status' => '1', 'order_by' => 'name'));
        return view('campaign::index', $this->data);
    }

    public function show($id)
    {
        $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
        $this->data['resultHolidays'] = Holiday::get(['date'])->pluck('date');
        $this->data['resultCampaignHistories'] = $this->campaignHistoryRepository->getCampaignHistory(base64_decode($id), ['order_by_desc' => 'created_at']);
        //dd($this->data['resultCampaign']->toArray());
        return view('campaign::show', $this->data);
    }

    public function create()
    {
        $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->getAll(array('status' => '1', 'order_by' => 'name'));
        $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->getAll(array('status' => '1', 'order_by' => 'name'));
        $this->data['resultCountries'] = Country::get();
        $this->data['resultRegions'] = DB::table('regions')->get();
        $this->data['resultHolidays'] = Holiday::get(['date'])->pluck('date');
        return view('campaign::create', $this->data);
    }

    public function createNewLead($campaign_id, $id)
    {
        $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
        $this->data['resultHolidays'] = Holiday::get(['date'])->pluck('date');
        return view('campaign::create_new_lead', $this->data);
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function storeNewLead(Request $request)
    {
        $attributes = $request->all();
        $attributes['id'] = base64_decode($attributes['id']);
        $response = $this->leadDetailRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign.show', base64_encode($attributes['id']))->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function edit($id = null)
    {
        if(!empty($id)) {
            $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
            $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->getAll(array('status' => '1', 'order_by' => 'name'));
            $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->getAll(array('status' => '1', 'order_by' => 'name'));
            $this->data['resultCountries'] = Country::get();
            $this->data['resultRegions'] = DB::table('regions')->get();
            return view('campaign::edit', $this->data);
        } else {
            return redirect()->route('campaign');
        }

    }

    public function update($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->update(base64_decode($id),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function updateLeadDetails($campaign_id, Request $request)
    {
        $attributes = $request->all();
        $attributes['lead_id'] = base64_decode($attributes['lead_id']);
        $response = $this->leadDetailRepository->update($attributes['lead_id'],$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign.show', $campaign_id)->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function updateSubAllocations($campaign_id, Request $request)
    {
        $attributes = $request->all();
        $attributes['lead_id'] = base64_decode($attributes['lead_id']);
        $attributes['campaign_id'] = base64_decode($campaign_id);
        $response = $this->pacingDetailRepository->update($attributes['lead_id'],$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign.show', $campaign_id)->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function attachSpecification($campaign_id, Request $request)
    {
        $attributes = $request->all();
        //dd($attributes);
        $response = $this->campaignRepository->updateSpecification(base64_decode($campaign_id),$attributes);
        if($response['status'] == TRUE) {
            return response()->json(['status' => 'true', 'data' => $response['data']]);;
        } else {
            return response()->json(['status' => 'false']);;
        }
    }

    public function removeSpecification($specification_id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->removeSpecification(base64_decode($specification_id),$attributes);
        if($response['status'] == TRUE) {
            return 'true';
        } else {
            return 'false';
        }


    }

    public function destroy($id)
    {
        return back()->withInput()->with('error', 'Something went wrong');
        $response = $this->userRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return redirect()->route('user')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function validateVMailCampaignId(Request $request)
    {
        $campaign = Campaign::query();
        $campaign = $campaign->where('v_mail_campaign_id',strtoupper($request->v_mail_campaign_id));

        if($request->has('campaign_id')) {
            $campaign = $campaign->where('id', '!=', base64_decode($request->campaign_id));
        }

        if($campaign->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    //data-table
    public function getCampaigns(Request $request)
    {
        $filters = array_filter(json_decode($request->get('filters'), true));

        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $records = Campaign::query();

        $records = $records->with(['leadDetail' => function($leadDetail) use($filters) {
            if(isset($filters['campaign_status'])) {
                $leadDetail->whereIn('campaign_status', $filters['campaign_status']);
            }
        }]);
        $records = $records->with('countries.country.region');

        $search_arr = $request->get('search');
        $searchValue = $search_arr['value']; // Search value

        if(isset($searchValue) && $searchValue != "") {
            /*$records = $records->orWhereHas('user.userDetail', function ($userDetail) use($searchValue){
                $userDetail->where(DB::raw('CONCAT_WS(" ",first_name, last_name)'), "like", "%$searchValue%");
            });*/
            $records = $records->where("campaign_id", "like", "%$searchValue%");
            $records = $records->orWhere("name", "like", "%$searchValue%");
        }

        //Filters
        if(!empty($filters)) {
            //Filters
            if(isset($filters['start_date'])) {
                $records = $records->whereHas('leadDetails', function($leadDetails) use($filters) {
                    $leadDetails->where('start_date', '>=', date('Y-m-d', strtotime($filters['start_date'])));
                });
            }

            if(isset($filters['end_date'])) {
                $records = $records->whereHas('leadDetails', function($leadDetails) use($filters) {
                    $leadDetails->where('end_date', '<=', date('Y-m-d', strtotime($filters['end_date'])));
                });
            }

            if(isset($filters['campaign_status'])) {
                $records = $records->whereHas('leadDetail', function($leadDetail) use($filters) {
                    $leadDetail->whereIn('campaign_status', $filters['campaign_status']);
                });
            }

            if(isset($filters['delivery_day'])) {
                $records = $records->whereHas('leadDetail', function($leadDetail) use($filters) {
                    $leadDetail->whereHas('pacingDetails', function($pacingDetails) use($filters) {
                        $pacingDetails->whereIn('day', $filters['delivery_day']);
                    });
                });
            }

            if(isset($filters['due_in'])) {
                $records = $records->whereHas('leadDetail', function($leadDetail) use($filters) {
                    $date = date('Y-m-d');
                    switch ($filters['due_in']) {
                        case 'Today':
                            $leadDetail->where('end_date', '=', $date);
                            break;
                        case 'Tomorrow':
                            $date2 = date('Y-m-d', strtotime('+1 days'));
                            $leadDetail->where('end_date', '=', $date2);
                            break;
                        case '7 Days':
                            $date2 = date('Y-m-d', strtotime('+6 days'));
                            $leadDetail->whereBetween('end_date', [$date, $date2]);
                            break;
                        case 'Past Due':
                            $leadDetail->where('end_date', '<=', $date);
                            break;
                    }
                });
            }

            if(isset($filters['campaign_type_id'])) {
                $records = $records->where('campaign_type_id', $filters['campaign_type_id']);
            }

            if(isset($filters['campaign_filter_id'])) {
                $records = $records->where('campaign_filter_id', $filters['campaign_filter_id']);
            }

            if(isset($filters['country_id'])) {
                $records = $records->whereHas('countries', function ($countries) use($filters) {
                    $countries->whereIn('country_id', $filters['country_id']);
                });
            }

            if(isset($filters['region_id'])) {
                $records = $records->whereHas('countries.country', function ($countries) use($filters) {
                    $countries->whereHas('region', function ($region) use($filters) {
                        $region->whereIn('id', $filters['region_id']);
                    });
                });
            }
            //--Filters

        }

        $totalRecords = $totalRecordswithFilter = $records->count();

        $records = $records->orderByDesc('created_at');
        $records = $records->offset($offset);
        $records = $records->limit($limit);
        $records = $records->get();

        $data = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );

        return response()->json($data);
    }

    public function getLeadDetails($campaign_id, Request $request)
    {
        $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($campaign_id));
        return view('campaign::lead_details', $this->data);
    }

    //edit-sub-allocations
    public function getSubAllocations($lead_id, Request $request)
    {
        $this->data['resultLeadDetail'] = $this->leadDetailRepository->find(base64_decode($lead_id));
        $start    = (new \DateTime($this->data['resultLeadDetail']->start_date))->modify('first day of this month');
        $end      = (new \DateTime($this->data['resultLeadDetail']->end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $this->data['monthList'] = array();
        $this->data['total_sub_allocation'] = 0;
        foreach ($period as $dt) {
            $monthData = [];
            //get month name
            $monthData['name'] = $dt->format("M-Y");
            $monthData['pacing_details'] = [];

            //get days list of pacing
            $first = (new \DateTime($dt->format("Y-m-d")))->modify('first day of this month')->format("Y-m-d");
            $last = (new \DateTime($dt->format("Y-m-d")))->modify('last day of this month')->format("Y-m-d");
            $resultPacingDetails = PacingDetail::where('lead_detail_id', base64_decode($lead_id))->whereBetween('date', [$first,$last])->get();
            if($this->data['resultLeadDetail']->pacing == 'Daily') {
                $monthData['days'] = $resultPacingDetails->pluck('day')->unique()->toArray();
            }

            $monthData['month'] = (new \DateTime($dt->format("Y-m-d")))->modify('first day of this month')->format("m");
            $monthData['year'] = (new \DateTime($dt->format("Y-m-d")))->modify('first day of this month')->format("Y");

            foreach ($resultPacingDetails as $pacingDetail) {
                $monthData['pacing_details'][$pacingDetail->date]['sub_allocation'] = $pacingDetail->sub_allocation;
                $monthData['pacing_details'][$pacingDetail->date]['title'] = date('D d-M-Y', strtotime($pacingDetail->date));
                $monthData['pacing_details'][$pacingDetail->date]['is_holiday'] = $pacingDetail->is_holiday;

                $this->data['total_sub_allocation'] = $this->data['total_sub_allocation'] + $pacingDetail->sub_allocation;
            }
            array_push($this->data['monthList'], $monthData);
        }
        //dd($this->data['monthList']);
        return view('campaign::edit_sub_allocations', $this->data);
    }

    public function getCampaignHistory($campaign_id, Request $request)
    {
        $attributes = $request->all();
        //dd($attributes);
        $attributes['order_by_desc'] = 'created_at';
        $this->data['resultCampaignHistories'] = $this->campaignHistoryRepository->getCampaignHistory(base64_decode($campaign_id), $attributes);
        return view('campaign::get_campaign_history_list_item', $this->data);
    }

    public function export($campaignId = null)
    {
        $filename = "campaign_list_" . time() . ".xlsx";

        if(null != $campaignId) {

            $campaign = Campaign::find(base64_decode($campaignId));

            $filename = $campaign->campaign_id . ".xlsx";
        }

        return Excel::download(new CampaignExport($campaignId), $filename);
    }

    public function campaignBulkImport(Request $request)
    {
        $attributes = $request->all();
        $fileList = array();
        if($request->hasFile('specification_file')) {
            $zip = Zip::open($request->file('specification_file'));
            $fileList = $zip->listFiles();
        }


        $excelData = Excel::toArray('', $request->file('campaign_file'));

        $validatedData = array();
        $invalidData = array();
        $errorMessages = array();
        foreach ($excelData[0] as $key => $row) {
            if($key != 0) {
                $response = $this->campaignRepository->validateCampaignData($row);
                if($response['status'] == TRUE) {
                    array_push($validatedData, $response['validatedData']);
                } else {
                    $row[5] = date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]), 'm/d/Y');
                    $row[6] = date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]), 'm/d/Y');
                    $tempArray['data'] = $row;
                    $tempArray['invalidCells'] = $response['invalidCells'];
                    $tempArray['errorMessage'] = implode(',', $response['errorMessage']);
                    array_push($invalidData, $tempArray);
                }
            }

        }

        //Insert validated data
        $importedCampaigns = 0;
        foreach ($validatedData as $index => $attributes) {

            $response = $this->campaignRepository->store($attributes);

            if($response['status'] == TRUE) {
                if(!empty($fileList)) {
                    $campaign_path = 'public/storage/campaigns/'.$response['campaign_id'].'/';
                    $unzips_path = 'public/storage/unzips';
                    foreach ($fileList as $filename) {
                        $exploded = explode('/', $filename);
                        if($attributes['name'] == $exploded[0]) {
                            $zip->extract($unzips_path, $filename);
                            if(!File::exists($campaign_path)) {
                                File::makeDirectory($campaign_path, $mode = 0777, true, true);
                            }
                            File::move($unzips_path.'/'.$exploded[0].'/'.$exploded[1], $campaign_path.$exploded[1]);
                            File::deleteDirectory($unzips_path.'/'.$exploded[0]);
                            CampaignSpecification::insert([['campaign_id' => $response['id'], 'file_name' => explode('/', $filename)[1]]]);
                        }
                    }
                }
            }
            $importedCampaigns++;
        }

        if(!empty($invalidData)) {
            //Generate CSV
            return  Excel::download(new ArrayToExcel($invalidData), 'InvalidCampaigns'. time() . ".xlsx");
            //return response()->json(['status' => false, 'response' => $importedCampaigns.' campaigns imported successfully', 'blob' => $file]);
        } else {
            return response('All Campaigns imported successfully', 201);
        }

    }
}

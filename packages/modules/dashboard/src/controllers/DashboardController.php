<?php


namespace Modules\Dashboard\controllers;


use App\Country;
use App\Http\Controllers\Controller;
use App\Region;
use App\Repository\Campaign\CampaignRepository;
use App\Repository\CampaignFilter\CampaignFilterRepository;
use App\Repository\CampaignType\CampaignTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\enum\CampaignStatus;
use Modules\Campaign\models\Campaign;
use Modules\Campaign\models\LeadDetail;

class DashboardController extends Controller
{
    private $data;
    private $campaignRepository;
    private $campaignTypeRepository;
    private $campaignFilterRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        CampaignTypeRepository $campaignTypeRepository,
        CampaignFilterRepository $campaignFilterRepository
    )
    {
        $this->data = array();
        $this->campaignRepository = $campaignRepository;
        $this->campaignTypeRepository = $campaignTypeRepository;
        $this->campaignFilterRepository = $campaignFilterRepository;
    }

    public function index_v3()
    {
        
        return view('dashboard::index', $this->data);
    }

    public function index_v1()
    {
        return view('dashboard::index_v1', $this->data);
    }

    public function index_v2()
    {
        return view('dashboard::index_v2', $this->data);
    }

    public function index()
    {
        $this->data['resultCountries'] = Country::get();
        $this->data['resultRegions'] = Region::get();
        $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->getAll(array('status' => '1', 'order_by' => 'name'));
        $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->getAll(array('status' => '1', 'order_by' => 'name'));
        return view('dashboard::index_v3', $this->data);
    }

    public function getDashboardData(Request $request)
    {
        $response = [];

        $leadDetails = LeadDetail::query();
        $totalLeads = LeadDetail::query();
        $leadDetails = $leadDetails->select(['campaign_status', DB::raw('COUNT(*) as total')]);
        //Filter start
        if($request->has('month') && $request->month != null) {
            $leadDetails = $leadDetails->whereMonth('start_date', '<=', $request->month);
            $leadDetails = $leadDetails->whereMonth('end_date', '>=', $request->month);
        }

        if($request->has('start_date') && $request->start_date != null) {

            $leadDetails = $leadDetails->where('start_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if($request->has('end_date') && $request->start_date != null) {

            $leadDetails = $leadDetails->where('end_date', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        //Filter end

        $leadDetails = $leadDetails->groupBy('campaign_status');
        $leadDetails = $leadDetails->get();
        $totalLeads = $totalLeads->count();
        $data = [];
        foreach($leadDetails as $status) {
            $data['count'][CampaignStatus::CAMPAIGN_STATUS[$status->campaign_status]] = $status->total;
            $data['percentage'][CampaignStatus::CAMPAIGN_STATUS[$status->campaign_status]] = ($status->total / $totalLeads * 100);
        }

        $response['data'] = $data;
        $response['message'] = "Record fetched successfully.";

        return response()->json($response);
    }

    public function getDashboardData_v1(Request $request)
    {

        $year = date('Y');
        $ind = 0;

        $campaignmonth = $request->all('campaignmonth');
        $startdate =  $request->all('campaigstartdate');
        $enddate = $request->all('campaigenddate');
        //dd($request->all());
        if(($campaignmonth['campaignmonth'] != null ))
        {
            $campaignmonth = $request->all('campaignmonth');
            $startdate = ''.$year.'-'.$campaignmonth['campaignmonth'].'-1';
            $enddate = ''.$year.'-'.$campaignmonth['campaignmonth'].'-31';
            $ind = 1;
        }else if ($startdate['campaigstartdate'] != null || $enddate['campaigenddate'] != null) {
            //dd($request->all('campaigstartdate'),$request->all('campaigenddate'));
            $startdate =  $request->all('campaigstartdate');
            $enddate = $request->all('campaigenddate');
            $startdate = date("Y-m-d", strtotime($startdate['campaigstartdate']));
            $enddate = date("Y-m-d", strtotime($enddate['campaigenddate']));
            if ($enddate == '1970-01-01') {$enddate = date('Y-m-d');}
            $ind = 1;
        }
        //dd($ind, $startdate, $enddate);
        $response = [];
        if ($ind != 1) {

            // All Count
            $leadCount['Live'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->count();
            $leadCount['Pause'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->count();
            $leadCount['Cancelled'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->count();
            $leadCount['Delivered'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->count();
            $leadCount['Reactivated'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->count();
            $leadCount['Shortfall'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->count();

            // TA Count
            $leadCount['LiveTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(1)->count();
            $leadCount['PauseTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(1)->count();
            $leadCount['CancelledTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(1)->count();
            $leadCount['DeliveredTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(1)->count();
            $leadCount['ReactivatedTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(1)->count();
            $leadCount['ShortfallTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(1)->count();

            // INT Count
            $leadCount['LiveINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(2)->count();
            $leadCount['PauseINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(2)->count();
            $leadCount['CancelledINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(2)->count();
            $leadCount['DeliveredINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(2)->count();
            $leadCount['ReactivatedINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(2)->count();
            $leadCount['ShortfallINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(2)->count();

            // LC Count
            $leadCount['LiveLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(3)->count();
            $leadCount['PauseLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(3)->count();
            $leadCount['CancelledLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(3)->count();
            $leadCount['DeliveredLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(3)->count();
            $leadCount['ReactivatedLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(3)->count();
            $leadCount['ShortfallLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(3)->count();
            //dd(($leadCount));
            // NC Count
            $leadCount['LiveNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(4)->count();
            $leadCount['PauseNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(4)->count();
            $leadCount['CancelledNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(4)->count();
            $leadCount['DeliveredNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(4)->count();
            $leadCount['ReactivatedNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(4)->count();
            $leadCount['ShortfallNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(4)->count();

            // Total Count
            $leadCount['ALLTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->count();
            $leadCount['TATOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(1)->count();
            $leadCount['INTTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(2)->count();
            $leadCount['LCTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(3)->count();
            $leadCount['NCTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(4)->count();
        }
        else{
            // All Count
            $leadCount['Live'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['Pause'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['Cancelled'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['Delivered'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['Reactivated'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['Shortfall'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();

            // TA Count
            $leadCount['LiveTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['PauseTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['CancelledTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['DeliveredTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ReactivatedTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ShortfallTA'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();

            // INT Count
            $leadCount['LiveINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['PauseINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['CancelledINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['DeliveredINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ReactivatedINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ShortfallINT'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();

            // LC Count
            $leadCount['LiveLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['PauseLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['CancelledLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['DeliveredLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ReactivatedLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ShortfallLC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();

            // NC Count
            $leadCount['LiveNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(1)->whereCampaignTypeId(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['PauseNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(2)->whereCampaignTypeId(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['CancelledNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(3)->whereCampaignTypeId(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['DeliveredNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(4)->whereCampaignTypeId(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ReactivatedNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(5)->whereCampaignTypeId(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['ShortfallNC'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereCampaignStatus(6)->whereCampaignTypeId(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();

            // Total Count
            $leadCount['ALLTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['TATOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(1)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['INTTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(2)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['LCTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(3)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
            $leadCount['NCTOTAL'] = Campaign::select('*')->join('lead_details', 'lead_details.campaign_id', '=', 'campaigns.id')->whereIn('campaign_status', array(1, 2, 3, 4, 5, 6))->whereCampaignTypeId(4)->where('start_date', '>=', $startdate)->where('end_date', '<=', $enddate)->count();
        }

        // /dd(($leadCount));
        return response()->json($leadCount);



    }

    public function getDashboardData_v3(Request $request)
    {
        $response = [];
        $campaignStatus = CampaignStatus::CAMPAIGN_STATUS;
        $campaignTypes = $this->campaignTypeRepository->getAll(['status' => '1']);

        $counts = array();
        $chartData = array();
        foreach ($campaignStatus as $key => $value) {
            $subData = array();
            $campaigns = Campaign::query();
            if(Auth::user()->role_id == '34') {
                $campaigns->with(['user' => function($users){
                    $users->whereUserId(Auth::id());
                }]);
                $campaigns->whereHas('user', function ($campaignUsers){
                    $campaignUsers->whereUserId(Auth::id());
                });
            }
            $totalCampaigns = $campaigns->count();
            $campaigns->whereHas('leadDetails', function($leadDetails) use($key, $value, $request) {
                $leadDetails->whereCampaignStatus($key);
            });
            $count = $campaigns->count();
            $counts[$value]['count'] = $count;
            $counts[$value]['percentage'] = number_format((float) ($count * 100 ) /  $totalCampaigns, 2, '.', '');

            $chartData[$key]['status'] = $value;
            $chartData[$key]['count'] = $count;

            foreach ($campaignTypes as $key2 => $campaignType) {
                $campaigns->whereCampaignTypeId($campaignType->id);
                $result = $campaigns->count();
                $chartData[$key]['subData'][$key2]['name'] = $campaignType->name;
                $chartData[$key]['subData'][$key2]['value'] = $result;
                $chartData[$key]['subData'][$key2]['value'] = Campaign::whereHas('leadDetails', function($leadDetails) use($key, $value, $request) {
                    $leadDetails->whereCampaignStatus($key);
                })->whereCampaignTypeId($campaignType->id)->count();

            }
        }

        /*
        //Filter start
        if($request->has('month') && $request->month != null) {
            $leadDetails = $leadDetails->whereMonth('start_date', '<=', $request->month);
            $leadDetails = $leadDetails->whereMonth('end_date', '>=', $request->month);
        }

        if($request->has('start_date') && $request->start_date != null) {

            $leadDetails = $leadDetails->where('start_date', '>=', date('Y-m-d', strtotime($request->start_date)));
        }
        if($request->has('end_date') && $request->start_date != null) {

            $leadDetails = $leadDetails->where('end_date', '<=', date('Y-m-d', strtotime($request->end_date)));
        }
        //Filter end
        */

        $response['data'] = $counts;
        $response['chartData'] = $chartData;
        $response['message'] = "Record fetched successfully.";

        return response()->json($response);
    }


}

<?php


namespace Modules\Dashboard\controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\enum\CampaignStatus;
use Modules\Campaign\models\LeadDetail;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('dashboard::index');
    }

    public function getDashboardData(Request $request)
    {
        $response = [];

        $leadDetails = LeadDetail::query();
        $totalLeads = LeadDetail::query();

        $leadDetails = $leadDetails->select(['campaign_status', DB::raw('COUNT(*) as total')]);

        /*$leadDetails = $leadDetails->whereIn('campaign_status', [
            CampaignStatus::LIVE,
            CampaignStatus::DELIVERED,
            CampaignStatus::PAUSED,
            CampaignStatus::CANCELLED
        ]);*/

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
}

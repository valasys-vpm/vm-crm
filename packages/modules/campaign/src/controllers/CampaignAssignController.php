<?php


namespace Modules\Campaign\controllers;


use App\Repository\Campaign\CampaignRepository;
use App\Repository\CampaignAssign\CampaignAssignRepository;
use App\Repository\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Campaign\models\Campaign;
use Modules\Campaign\models\CampaignType;

class CampaignAssignController
{
    private $data;
    private $campaignAssignRepository;
    private $campaignRepository;
    private $userRepository;

    public function __construct(
        CampaignAssignRepository $campaignAssignRepository,
        CampaignRepository $campaignRepository,
        UserRepository $userRepository
    )
    {
        $this->data = array();
        $this->campaignAssignRepository = $campaignAssignRepository;
        $this->campaignRepository = $campaignRepository;
        $this->userRepository =$userRepository;
    }

    public function index()
    {
        $this->data['resultCampaignsToAssign'] = $this->campaignAssignRepository->getNotAssignedCampaigns();
        //dd($this->data['resultCampaignsToAssign']->toArray());
        $this->data['resultUsersToAssign'] = $this->campaignAssignRepository->getUsersToAssign();
        //dd($this->data['resultUsersToAssign']->toArray());
        $this->data['resultCampaignTypes'] = [];
        return view('campaign::campaign_assign.index', $this->data);
    }

    public function create()
    {
        return view('campaign::campaign_assign.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        //dd($attributes);
        $response = $this->campaignAssignRepository->store($attributes);
        if($response['status'] == TRUE) {
            //return redirect()->route('campaign_assign')->with('success', $response['message']);
            return response()->json(['status' => true, 'message' => $response['message']]);
        } else {
            return response()->json(['status' => false, 'message' => $response['message']]);
        }
    }

    public function edit($id)
    {
        $this->data['resultCampaignType'] = $this->campaignTypeRepository->find(base64_decode($id));
        return view('campaign::campaign_type.edit', $this->data);
    }

    public function update($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignTypeRepository->update(base64_decode($id),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign_type')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function destroy($id)
    {
        $response = $this->campaignTypeRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return redirect()->route('campaign_type')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
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

        if(Auth::user()->role_id == '34') {
            $records->with(['users' => function($users) use ($filters){
                $users->whereAssignedBy(Auth::id());
            }]);
            $records->whereHas('users', function ($campaignUsers) use ($filters){
                $campaignUsers->whereUserId(Auth::id());
            });
        }

        if(Auth::user()->role_id == '34') {
            $records->with(['user' => function($users) use ($filters){
                $users->whereUserId(Auth::id());
            }]);
        }

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
        //dd($records->toArray());
        $data = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );

        return response()->json($data);
    }


}

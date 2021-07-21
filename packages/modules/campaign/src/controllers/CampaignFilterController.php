<?php


namespace Modules\Campaign\controllers;


use App\Repository\CampaignFilter\CampaignFilterRepository;
use Illuminate\Http\Request;
use Modules\Campaign\models\CampaignFilter;

class CampaignFilterController
{
    private $data;
    private $campaignFilterRepository;

    public function __construct(CampaignFilterRepository $campaignFilterRepository)
    {
        $this->data = array();
        $this->campaignFilterRepository = $campaignFilterRepository;
    }

    public function index()
    {
        $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->getAll();
        return view('campaign::campaign_filter.index', $this->data);
    }

    public function create()
    {
        return view('campaign::campaign_filter.create');
    }

    public function edit($id)
    {
        $this->data['resultCampaignFilter'] = $this->campaignFilterRepository->find(base64_decode($id));
        return view('campaign::campaign_filter.edit', $this->data);
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignFilterRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign_filter')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function update($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignFilterRepository->update(base64_decode($id),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign_filter')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function destroy($id)
    {
        $response = $this->campaignFilterRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return redirect()->route('campaign_filter')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function validateName(Request $request)
    {
        $campaign_filter = CampaignFilter::query();
        $campaign_filter = $campaign_filter->whereName(strtoupper($request->name));

        if($request->has('campaign_filter_id')) {
            $campaign_filter = $campaign_filter->where('id', '!=', base64_decode($request->campaign_filter_id));
        }

        if($campaign_filter->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }



}

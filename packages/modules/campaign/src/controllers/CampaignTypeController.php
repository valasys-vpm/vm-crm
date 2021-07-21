<?php


namespace Modules\Campaign\controllers;


use App\Repository\CampaignType\CampaignTypeRepository;
use Illuminate\Http\Request;
use Modules\Campaign\models\CampaignType;

class CampaignTypeController
{
    private $data;
    private $campaignTypeRepository;

    public function __construct(CampaignTypeRepository $campaignTypeRepository)
    {
        $this->data = array();
        $this->campaignTypeRepository = $campaignTypeRepository;
    }

    public function index()
    {
        $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->getAll();
        return view('campaign::campaign_type.index', $this->data);
    }

    public function create()
    {
        return view('campaign::campaign_type.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignTypeRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('campaign_type')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
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

    public function validateName(Request $request)
    {
        $campaignType = CampaignType::query();
        $campaignType = $campaignType->whereName(strtoupper($request->name));

        if($request->has('campaign_type_id')) {
            $campaignType = $campaignType->where('id', '!=', base64_decode($request->campaign_type_id));
        }

        if($campaignType->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }


}

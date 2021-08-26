<?php

namespace App\Repository\CampaignAssign;

use App\Helper\Helper;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\models\Campaign;
use Modules\Campaign\models\CampaignUsers;

class CampaignAssignRepository implements CampaignAssignInterface
{

    private $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function getNotAssignedCampaigns($filters = array())
    {
        $query = $this->campaign->whereNotNull('id');
        $query->with('leadDetail');
        $query->whereHas('leadDetail', function ($leadDetails) use ($filters){
            $leadDetails->whereIn('campaign_status', ['1','2','5','6']);
        });

        if(Auth::user()->role_id == '34') {
            $query->with(['users' => function($users) use ($filters){
                $users->whereUserId(Auth::id());
            }]);
            $query->whereHas('users', function ($campaignUsers) use ($filters){
                $campaignUsers->whereUserId(Auth::id());
            });
            $query->whereDoesntHave('users', function ($campaignUsers) use ($filters){
                $campaignUsers->whereAssignedBy(Auth::id());
            });
        } else {
            $query->whereDoesntHave('users');
        }

        if(isset($filters['order_by']) && $filters['order_by']) {
            $query->orderBy($filters['order_by']);
        }

        return $query->get();
    }

    public function getUsersToAssign($filters = array())
    {
        $query = User::query();

        if(isset($filters['order_by']) && $filters['order_by']) {
            $query->orderBy($filters['order_by']);
        }

        $query->with(['userDetail','role']);

        if(Auth::user()->role_id == '34') {
            $query->whereHas('userDetail', function ($userDetail) use ($filters){
                $userDetail->whereReportingManagerId(Auth::id());
            });
        } else {
            $query->whereNotIn('id', ['1',Auth::id()]);
        }



        $query->whereStatus('1');

        return $query->get();
    }


    public function get($filters = array())
    {
        // TODO: Implement get() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $insertCampaignUsers = array();
            foreach ($attributes['data'] as $key => $campaign) {
                foreach ($campaign['users'] as $user) {
                    array_push($insertCampaignUsers, array(
                        'campaign_id' => $campaign['campaign_id'],
                        'parent_id' => $campaign['parent_id'],
                        'user_id' => $user['user_id'],
                        'display_date' => date('Y-m-d', strtotime($campaign['display_date'])),
                        'allocation' => $user['allocation'],
                        'assigned_by' => Auth::id()
                    ));
                }
            }
            if(CampaignUsers::insert($insertCampaignUsers)) {
                //Save History
                //$this->historyRepository->store(array('route' => 'campaign_assign.store', 'action' => 'Assigned campaign(s) to user(s)', 'value' => array('id' => $campaign_filter->id, 'message' => Helper::getCreatedMessage('Campaign Filter', $campaign_filter->name))));
                //--Save History
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign assigned successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }


}

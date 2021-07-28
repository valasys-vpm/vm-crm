<?php


namespace App\Repository\User;


use App\Helper\Helper;
use App\Repository\History\HistoryRepository;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\History\controllers\HistoryController;
use Modules\Role\models\Role;
use Modules\User\enum\UserStatus;
use Modules\User\models\UserDetail;

class UserRepository implements UserInterface
{
    private $user;
    private $userDetail;
    private $historyController;
    private $historyRepository;

    public function __construct(
        User $user,
        UserDetail $userDetail,
        HistoryController $historyController,
        HistoryRepository $historyRepository
    )
    {
        $this->data = array();
        $this->user = $user;
        $this->userDetail = $userDetail;
        $this->historyController = $historyController;
        $this->historyRepository = $historyRepository;
    }

    public function getAll($filters = array())
    {
        $query = $this->user->whereNotNull('id');
        if(isset($filters['order_by']) && $filters['order_by']) {
            $query->orderBy($filters['order_by']);
        }
        if(isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        if(isset($filters['with_user_details']) && $filters['with_user_details']) {
            $query->with('userDetail');
        }
        if(isset($filters['with_role']) && $filters['with_role']) {
            $query->with('role');
        }
        if(isset($filters['except_id']) && !empty($filters['except_id'])) {
            $query->whereNotIn('id', $filters['except_id'] );
        }
        if(isset($filters['where_user_not_in']) && !empty($filters['where_user_not_in'])) {
            $query->whereNotIn('id', $filters['where_user_not_in']);
        }
        return $query->get();
    }

    public function find($id)
    {
        return $this->user->with(['userDetail','role'])->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $user = new User();
            $user->role_id = $attributes['role_id'];
            $user->email = $attributes['email'];
            $user->password = $attributes['password'];
            $user->status = $attributes['status'];
            $user->save();
            if($user->id) {
                $userDetail = new UserDetail();
                $userDetail->user_id = $user->id;
                $userDetail->first_name = $attributes['first_name'];
                $userDetail->last_name = $attributes['last_name'];
                $userDetail->emp_code = strtoupper($attributes['emp_code']);
                $userDetail->department = $attributes['department'];
                $userDetail->designation = $attributes['designation'];
                $userDetail->reporting_manager_id  = $attributes['reporting_manager_id'];
                $userDetail->save();
                if($userDetail->id) {
                    //Save History
                    $historyArray = array(
                        'route' => 'user.create',
                        'action' => 'Created new user',
                        'value' => array('id' => $user->id, 'message' => Helper::getCreatedMessage('User', $userDetail->first_name.' '.$userDetail->last_name))
                    );
                    $this->historyRepository->store($historyArray);
                    //--Save History
                    DB::commit();
                    $response = array('status' => TRUE, 'message' => 'User created successfully');
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $user = $this->user->find($id);
            $userCopy = $user->toArray();
            if($id == Auth::id()) {
                if(isset($attributes['password'])) {
                    $user->password = Hash::make($attributes['password']);
                    $userCopy['password'] = 'Password Updated';
                }
            } else {
                if(isset($attributes['role_id'])) {
                    $user->role_id = $attributes['role_id'];
                }
                if(isset($attributes['email'])) {
                    $user->email = $attributes['email'];
                }
                if(isset($attributes['password'])) {
                    $user->password = Hash::make($attributes['password']);
                    $userCopy['password'] = 'Password Updated';
                }
                if(isset($attributes['status'])) {
                    $user->status = $attributes['status'];
                }
            }
            $user->save();
            if($user->id) {
                $userDetail = $user->userDetail;
                $userDetailCopy = $userDetail->toArray();
                if($id == Auth::id()) {
                    if(isset($attributes['first_name'])) {
                        $userDetail->first_name = $attributes['first_name'];
                    }
                    if(isset($attributes['last_name'])) {
                        $userDetail->last_name = $attributes['last_name'];
                    }
                } else {
                    if(isset($attributes['first_name'])) {
                        $userDetail->first_name = $attributes['first_name'];
                    }
                    if(isset($attributes['last_name'])) {
                        $userDetail->last_name = $attributes['last_name'];
                    }
                    if(isset($attributes['emp_code'])) {
                        $userDetail->emp_code = strtoupper($attributes['emp_code']);
                    }
                    if(isset($attributes['department'])) {
                        $userDetail->department = $attributes['department'];
                    }
                    if(isset($attributes['designation'])) {
                        $userDetail->designation = $attributes['designation'];
                    }
                    if(isset($attributes['reporting_manager_id'])) {
                        $userDetail->reporting_manager_id  = $attributes['reporting_manager_id'];
                    }
                }
                $userDetail->save();
                if($userDetail->id) {
                    //Save History
                    $updatedUserData = Helper::getUpdatedData($user->getChanges(), $userCopy);
                    $updatedUserDetailData = Helper::getUpdatedData($userDetail->getChanges(), $userDetailCopy);
                    $updatedData = array_merge($updatedUserData,$updatedUserDetailData);
                    $message = 'Updated User - '.$userDetail->first_name.' '.$userDetail->first_name.'<br>Updated Fields are:';
                    foreach ($updatedData as $key => $value) {
                        switch ($key) {
                            case 'status':
                                $message .= Helper::getUpdatedMessage($key, UserStatus::USER_STATUS[$value['new']], UserStatus::USER_STATUS[$value['old']]);
                                break;
                            case 'role_id':
                                $new = Role::findOrFail($value['new']);
                                $old = Role::findOrFail($value['old']);
                                $message .= Helper::getUpdatedMessage($key, $new->name, $old->name);
                                break;
                            case 'reporting_manager_id':
                                $new = $this->user->find($value['new']);
                                $old = $this->user->find($value['old']);
                                $message .= Helper::getUpdatedMessage($key, $new->userDetail->full_name, $old->userDetail->full_name);
                                break;
                            default:
                                $message .= Helper::getUpdatedMessage($key, $value['new'], $value['old']);
                        }
                    }
                    $historyArray = array(
                        'route' => 'user.edit',
                        'action' => 'Updated user',
                        'value' => array('id' => $user->id, 'data' => $updatedData, 'message' => $message)
                    );
                    $this->historyRepository->store($historyArray);
                    //--Save History
                    DB::commit();
                    $response = array('status' => TRUE, 'message' => 'User updated successfully');
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
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

    public function destroy($id)
    {
        $user = $this->user->find($id);
        $userName = $user->userDetail->full_name;
        if($user->delete()) {
            //Save History
            $historyArray = array(
                'route' => 'user.destroy',
                'action' => 'Deleted User',
                'value' => array('id' => $id, 'message' => Helper::getDeletedMessage('User', $userName))
            );
            $this->historyRepository->store($historyArray);
            //--Save History
            return $response = array('status' => TRUE, 'message' => 'User deleted successfully');
        } else {
            return $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
    }



}

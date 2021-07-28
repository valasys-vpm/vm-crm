<?php


namespace App\Repository\Role;


use App\Helper\Helper;
use App\Repository\History\HistoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\History\controllers\HistoryController;
use Modules\Permission\models\Permission;
use Modules\Role\enum\RoleStatus;
use Modules\Role\models\Role;
use Modules\Role\models\RolePermission;
use \Illuminate\Support\Facades\Request;

class RoleRepository implements RoleInterface
{
    private $role;
    private $rolePermission;
    private $historyController;
    private $historyRepository;

    public function __construct(
        Role $role,
        RolePermission $rolePermission,
        HistoryController $historyController,
        HistoryRepository $historyRepository
    )
    {
        $this->role = $role;
        $this->rolePermission = $rolePermission;
        $this->historyController = $historyController;
        $this->historyRepository = $historyRepository;
    }

    public function getAll($filters = array())
    {
        $query = $this->role->whereNotNull('id');
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
        return $this->role->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $role = new Role();
            $role->name = $attributes['name'];
            $role->status = $attributes['status'];
            $role->save();
            if($role->id) {
                //Save History
                $historyArray = array(
                    'route' => 'role.create',
                    'action' => 'Created new role',
                    'value' => array('id' => $role->id, 'message' => Helper::getCreatedMessage('Role', $role->name))
                );
                $this->historyRepository->store($historyArray);
                //--Save History
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Role created successfully');
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
            $role = $this->role->find($id);
            $roleCopy = $role->toArray();
            $role->name = $attributes['name'];
            $role->status = $attributes['status'];
            $role->save();
            if($role->id) {

                //Save History
                $updatedData = Helper::getUpdatedData($role->getChanges(), $roleCopy);
                $message = 'Updated Role - '.$role->name.'<br>Updated Fields are:';
                foreach ($updatedData as $key => $value) {
                    if($key == 'status') {
                        $message .= Helper::getUpdatedMessage($key, RoleStatus::ROLE_STATUS[$value['new']], RoleStatus::ROLE_STATUS[$value['old']]);
                    } else {
                        $message .= Helper::getUpdatedMessage($key, $value['new'], $value['old']);
                    }
                }
                $historyArray = array(
                    'route' => 'role.edit',
                    'action' => 'Updated role',
                    'value' => array('id' => $role->id, 'data' => $updatedData, 'message' => $message)
                );
                $this->historyRepository->store($historyArray);
                //--Save History

                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Role updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id)
    {
        $role = $this->role->find($id);
        $roleName = $role->name;
        if($role->delete()) {
            //Save History
            $historyArray = array(
                'route' => 'role.destroy',
                'action' => 'Deleted Role',
                'value' => array('id' => $id, 'message' => Helper::getDeletedMessage('Role', $role->name))
            );
            $this->historyRepository->store($historyArray);
            //--Save History
            return $response = array('status' => TRUE, 'message' => 'Role deleted successfully');
        } else {
            return $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
    }

    public function managePermissionStore($id, $attributes = array())
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $this->rolePermission->whereRoleId($id)->delete();

            $rolePermissionArray = array();
            foreach ($attributes['ids'] as $row => $value) {
                $rolePermissionArray[$row] = [
                    'role_id' => $id,
                    'permission_id' => $value
                ];
            }

            if(RolePermission::insert($rolePermissionArray)) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Role Permission updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

}

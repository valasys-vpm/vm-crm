<?php


namespace App\Repository\Permission;


use Illuminate\Support\Facades\DB;
use Modules\Permission\models\Permission;

class PermissionRepository implements PermissionInterface
{
    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function getAll($filters = array())
    {
        $query = $this->permission->whereNotNull('id');
        if(isset($filters['order_by']) && $filters['order_by']) {
            $query->orderBy($filters['order_by']);
        }
        if(isset($filters['is_module']) && $filters['is_module']) {
            $query->where('is_module', $filters['is_module']);
        }
        if(isset($filters['parent_id'])) {
            if($filters['parent_id'] == '') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $filters['parent_id']);
            }
        }

        if(isset($filters['is_module'])) {
            $query->whereIsModule($filters['is_module']);
        }

        if(isset($filters['with_submodule']) && $filters['with_submodule']) {
            //$query->with('subPermissions');
            $query->with(['subPermissions' => function($subPermission) use($filters) {
                $subPermission->with(['rolePermissions' => function($rolePermission) use($filters) {
                    $rolePermission->whereRoleId($filters['with_role_permission']);
                }]);
                if(isset($filters['status']) && $filters['status']) {
                    $subPermission->where('status', $filters['status']);
                }
            }]);
        }
        if(isset($filters['with_role_permission']) && $filters['with_role_permission']) {
            $query->with(['rolePermissions' => function($rolePermission) use($filters) {
                $rolePermission->whereRoleId($filters['with_role_permission']);
            }]);
        }
        if(isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        return $query->get();
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
            $permission = new Permission();
            $permission->name = $attributes['name'];
            $permission->slug = $attributes['slug'];
            $permission->parent_id = $attributes['parent_id'];
            $permission->route = $attributes['route'];
            $permission->icon = $attributes['icon'];
            $permission->sidebar_visibility = $attributes['sidebar_visibility'];
            $permission->priority = $attributes['priority'];
            $permission->is_module = $attributes['is_module'];
            $permission->status = $attributes['status'];
            $permission->save();
            if($permission->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Module created successfully');
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
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }



}

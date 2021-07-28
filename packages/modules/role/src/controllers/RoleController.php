<?php


namespace Modules\Role\controllers;

use App\Http\Controllers\Controller;
use App\Repository\Permission\PermissionRepository;
use App\Repository\Role\RoleRepository;
use Illuminate\Http\Request;
use Modules\History\controllers\HistoryController;
use Modules\Role\models\Role;

class RoleController extends Controller
{
    private $data;
    private $roleRepository;
    private $permissionRepository;
    private $historyController;

    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository, HistoryController $historyController)
    {
        $this->data = array();
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->historyController = $historyController;
    }

    public function index()
    {
        $this->data['resultRoles'] = $this->roleRepository->getAll();
        return view('role::index', $this->data);
    }

    public function create()
    {
        return view('role::create');
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $response = $this->roleRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('role')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function edit($id)
    {
        $this->data['resultRole'] = $this->roleRepository->find(base64_decode($id));
        return view('role::edit', $this->data);
    }

    public function update($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->roleRepository->update(base64_decode($id),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('role')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function destroy($id)
    {
        $response = $this->roleRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return redirect()->route('role')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function validateName(Request $request)
    {
        $role = Role::query();
        $role = $role->whereName($request->name);

        if($request->has('role_id')) {
            $role = $role->where('id', '!=', base64_decode($request->role_id));
        }

        if($role->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function managePermission($id)
    {
        $this->data['resultRole'] = $this->roleRepository->find(base64_decode($id));
        $this->data['resultPermissions'] = $this->permissionRepository->getAll(['is_module' => '1','with_submodule' => '1', 'with_role_permission' => base64_decode($id), 'status' => '1']);
        //dd($this->data['resultPermissions']);
        //dd($this->data['resultPermissions']->toArray());
        return view('role::manage_permission', $this->data);
    }

    public function managePermissionStore($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->roleRepository->managePermissionStore(base64_decode($id), $attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('role')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }
}

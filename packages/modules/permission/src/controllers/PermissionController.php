<?php


namespace Modules\Permission\controllers;


use App\Http\Controllers\Controller;
use App\Repository\Permission\PermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    private $data;
    private $permissionRepository;
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->data = array();
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        $this->data['resultPermissions'] = $this->data['resultPermissions'] = $this->permissionRepository->getAll(['is_module' => '1']);
        return view('permission::index', $this->data);
    }

    public function create()
    {
        $this->data['resultPermissions'] = $this->permissionRepository->getAll(['is_module' => '1']);
        return view('permission::create', $this->data);
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $response = $this->permissionRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('permission')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

}

<?php


namespace Modules\User\controllers;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Repository\History\HistoryRepository;
use App\Repository\Role\RoleRepository;
use App\Repository\User\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\History\controllers\HistoryController;
use Modules\User\enum\UserStatus;
use Modules\User\models\UserDetail;

class UserController extends Controller
{
    private $data;
    private $roleRepository;
    private $userRepository;
    private $historyRepository;
    private $historyController;

    public function __construct(RoleRepository $roleRepository, UserRepository $userRepository, HistoryRepository $historyRepository, HistoryController $historyController)
    {
        $this->data = array();
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->historyRepository = $historyRepository;
        $this->historyController = $historyController;
    }

    public function index()
    {
        $this->data['resultUsers'] = $this->userRepository->getAll(['with_user_details' => '1','with_role' => '1', 'where_user_not_in' => [Auth::id()]]);
        return view('user::index', $this->data);
    }

    public function show($id)
    {
        $this->data['resultUser'] = $this->userRepository->find(base64_decode($id));
        $this->data['resultHistories'] = $this->historyRepository->getUserHistory(base64_decode($id));
        return view('user::show', $this->data);
    }

    public function profile()
    {
        $this->data['resultUser'] = $this->userRepository->find(Auth::id());
        return view('user::profile', $this->data);
    }

    public function create()
    {
        $this->data['resultRoles'] = $this->roleRepository->getAll(['status' => UserStatus::ACTIVE]);
        $this->data['resultUsers'] = $this->userRepository->getAll(['status' => UserStatus::ACTIVE, 'with_user_details' => '1']);
        return view('user::create', $this->data);
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $attributes['password'] = Hash::make('Valasys@#'.date('Y'));
        $response = $this->userRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('user')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function edit($id)
    {
        $this->data['resultUser'] = $this->userRepository->find(base64_decode($id));
        $this->data['resultRoles'] = $this->roleRepository->getAll(['status' => UserStatus::ACTIVE]);
        $this->data['resultUsers'] = $this->userRepository->getAll(['status' => UserStatus::ACTIVE, 'with_user_details' => '1', 'except_id' => [base64_decode($id)]]);
        return view('user::edit', $this->data);
    }

    public function update($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->userRepository->update(base64_decode($id),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('user')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function updateProfile(Request $request)
    {
        $attributes = $request->all();
        $response = $this->userRepository->update(Auth::id(),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function changePassword(Request $request)
    {
        $attributes = $request->all();
        $response = $this->userRepository->update(Auth::id(),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('user.profile')->with('success', 'Password updated successfully');
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function resetPassword(Request $request)
    {
        $attributes = $request->all();
        $response = $this->userRepository->update(base64_decode($attributes['user_id']),$attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('user')->with('success', 'Password updated successfully');
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    public function destroy($id)
    {
        $response = $this->userRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return redirect()->route('user')->with('success', $response['message']);
        } else {
            return back()->withInput()->with('error', $response['message']);
        }
    }

    //Validate Email - is existing
    public function validateEmail(Request $request)
    {
        $user = User::query();
        $user = $user->whereEmail($request->email);

        if($request->has('user_id')) {
            $user = $user->where('id', '!=', base64_decode($request->user_id));
        }

        if($user->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    //Validate Email - is existing
    public function validateEmployeeCode(Request $request)
    {
        $userDetail = UserDetail::query();
        $userDetail = $userDetail->whereEmpCode($request->emp_code);

        if($request->has('user_id')) {
            $userDetail = $userDetail->where('user_id', '!=', base64_decode($request->user_id));
        }

        if($userDetail->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function userLogoutForce($id)
    {
        $user = User::with('userDetail')->findOrFail(base64_decode($id));
        $user->logged_on = null;
        if($user->save()) {
            //Save History
            $historyArray = array(
                'route' => 'user.logout.force',
                'action' => 'Logout User',
                'value' => array('id' => $id, 'message' => 'Logout User - '.$user->userDetail->full_name.' ('.strtoupper($user->userDetail->emp_code).')')
            );
            $this->historyRepository->store($historyArray);
            //--Save History
            return redirect()->route('user')->with('success', 'User logout successfully');
        } else {
            return back()->withInput()->with('error', 'Something went wrong, please try again.');
        }
    }

}

<?php


namespace Modules\History\controllers;

use App\Http\Controllers\Controller;
use App\Repository\History\HistoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\History\models\History;
use Modules\Permission\Models\Permission;

class HistoryController extends Controller
{
    private $data;
    private $historyRepository;

    public function __construct(HistoryRepository $historyRepository)
    {
        $this->data = array();
        $this->historyRepository = $historyRepository;
    }

    public function index()
    {
        $this->data['resultHistories'] = $this->historyRepository->getAll(['with_user' => '1']);
        return view('history::index', $this->data);
    }

    public function store($route, $action = '', $value = [])
    {
        $attributes = array(
            'action' => $action,
            'value' => $value
        );
        $this->historyRepository->store($attributes);
    }

    /*
     * Ajax Method
     */
    public function getHistories(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $records = History::query();

        $records = $records->with('user.userDetail');

        $search_arr = $request->get('search');
        $searchValue = $search_arr['value']; // Search value

        if(isset($searchValue) && $searchValue != "") {
            $records = $records->orWhereHas('user.userDetail', function ($userDetail) use($searchValue){
                $userDetail->where(DB::raw('CONCAT_WS(" ",first_name, last_name)'), "like", "%$searchValue%");
            });
            $records = $records->orWhere("action", "like", "%$searchValue%");
            $records = $records->orWhere("value", "like", "%$searchValue%");
        }

        $totalRecords = $totalRecordswithFilter = $records->count();

        $records = $records->orderByDesc('created_at');

        $records = $records->offset($offset);
        $records = $records->limit($limit);

        $records = $records->get();

        $data = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );

        return response()->json($data);
    }
}

<?php


namespace App\Repository\History;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\History\models\History;
use Modules\Permission\models\Permission;

class HistoryRepository implements HistoryInterface
{
    private $history;
    public function __construct(History $history)
    {
        $this->history = $history;
    }

    public function getAll($filters = array())
    {
        $query = $this->history->whereNotNull('id');
        if(isset($filters['with_user']) && $filters['with_user']) {
            $query->with(['user' => function($user) {
                $user->with('userDetail');
            }]);
        }
        return $query->get();
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function store($attributes)
    {

        try {
            if(isset($attributes['value']['data']) && empty($attributes['value']['data'])) {
                return true;
            }
            $permission = Permission::whereRoute($attributes['route'])->first();
            DB::beginTransaction();
            $history = new History();
            $history->user_id = Auth::id();
            $history->permission_id = $permission->id;
            $history->action = $attributes['action'];
            $history->value = json_encode($attributes['value']);
            $history->save();
            if($history->id) {
                DB::commit();
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
        }
        return true;
    }

    public function getUserHistory($id)
    {
        $query = $this->history->whereUserId($id);
        return $query->get();
    }


}

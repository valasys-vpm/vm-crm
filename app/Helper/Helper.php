<?php


namespace App\Helper;


use App\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Modules\Permission\Models\Permission;
use Modules\Role\models\RolePermission;

class Helper
{
    public static function hasPermission($route)
    {
        $role_id = Auth::user()->role_id;
        $permission = Permission::select('id')->whereRoute($route)->first();
        if(!RolePermission::whereRoleId($role_id)->wherePermissionId($permission->id)->exists()) {
            return false;
        } else {
            return true;
        }
    }

    public static function getCreatedMessage($module, $title)
    {
        $message = 'Created new '.$module.' - '.$title;
        return $message;
    }

    public static function getUpdatedData($changes = [], $copy = [] )
    {
        $updatedData = array();
        foreach ($changes as $key => $value) {
            if($key == 'password') {
                $updatedData[$key] = ['new' => '********', 'old' => '********'];
            } else {
                $updatedData[$key] = ['new' => $value, 'old' => $copy[$key]];
            }

        }
        unset($updatedData['updated_at']);
        return $updatedData;
    }

    public static function getUpdatedMessage($key, $new, $old)
    {
        $key = str_replace('_id', '', $key);
        return '<br>- '.ucwords(str_replace('_',' ', $key)).': from <b>'.$old.'</b> to <b>'.$new.'</b>';
    }

    public static function getDeletedMessage($module, $title)
    {
        $message = 'Deleted '.$module.' - '.$title;
        return $message;
    }

    public static function getSiteSetting($key)
    {
        return SiteSetting::where('key',$key)->first()->value;
    }
}

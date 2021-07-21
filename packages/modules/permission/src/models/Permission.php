<?php

namespace Modules\Permission\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Role\models\RolePermission;

class Permission extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    public function subPermissions()
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id');
    }

    public function rolePermissions()
    {
        return $this->hasOne(RolePermission::class, 'permission_id', 'id');
    }

    public function children() {
        return $this->hasMany(static::class, 'parent_id');
    }
}

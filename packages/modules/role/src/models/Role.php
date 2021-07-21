<?php

namespace Modules\Role\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Role\enum\RoleStatus;

class Role extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

}

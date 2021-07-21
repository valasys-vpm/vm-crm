<?php


namespace App\Repository\Role;


interface RoleInterface
{
    public function getAll($filters);
    public function find($id);
    public function store($attributes);
    public function update($id, $attributes);
    public function destroy($id);

    public function managePermissionStore($id, $attributes);
}

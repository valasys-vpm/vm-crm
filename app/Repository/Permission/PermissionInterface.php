<?php


namespace App\Repository\Permission;


interface PermissionInterface
{
    public function getAll($filters);
    public function find($id);
    public function store($attributes);
    public function update($id, $attributes);
    public function destroy($id);
}

<?php


namespace App\Repository\Campaign\LeadDetail;


interface LeadDetailInterface
{
    public function getAll($filters = []);
    public function find($id);
    public function store($attributes);
    public function update($id, $attributes = []);
    public function destroy($id);
}

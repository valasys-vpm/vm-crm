<?php


namespace App\Repository\Campaign\PacingDetail;


interface PacingDetailInterface
{
    public function getAll($filters = []);
    public function find($id);
    public function store($attributes);
    public function update($id, $attributes = []);
    public function destroy($id);
}

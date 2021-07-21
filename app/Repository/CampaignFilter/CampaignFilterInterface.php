<?php


namespace App\Repository\CampaignFilter;


interface CampaignFilterInterface
{
    public function getAll($filters = array());
    public function find($id);
    public function store($attributes);
    public function update($id, $attributes);
    public function destroy($id);
}

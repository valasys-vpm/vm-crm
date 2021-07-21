<?php


namespace App\Repository\Campaign;


interface CampaignInterface
{
    public function getAll($filters = []);
    public function find($id);
    public function store($attributes);
    public function update($id, $attributes = []);
    public function updateSpecification($id, $attributes = []);
    public function destroy($id);
}

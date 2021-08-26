<?php

namespace App\Repository\CampaignAssign;

interface CampaignAssignInterface
{
    public function getNotAssignedCampaigns($filters = array());
    public function getUsersToAssign($filters = array());
    public function get($filters = array());
    public function find($id);
    public function store($attributes);
    public function update($id, $attributes);
    public function destroy($id);
}

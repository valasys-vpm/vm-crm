<?php


namespace App\Repository\Campaign\CampaignHistory;


interface CampaignHistoryInterface
{
    public function getAll($filters = array());
    public function getCampaignHistory($campaign_id, $filters = []);
    public function store($attributes);
}

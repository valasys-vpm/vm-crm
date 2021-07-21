<?php


namespace Modules\Campaign\enum;


class CampaignFilterStatus
{
    const ACTIVE = '1';
    const INACTIVE = '0';

    const CAMPAIGN_FILTER_STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive'
    ];
}

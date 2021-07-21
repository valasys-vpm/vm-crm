<?php


namespace Modules\Campaign\enum;


class CampaignTypeStatus
{
    const ACTIVE = '1';
    const INACTIVE = '0';

    const CAMPAIGN_TYPE_STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive'
    ];
}

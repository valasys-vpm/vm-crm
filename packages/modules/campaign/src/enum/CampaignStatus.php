<?php


namespace Modules\Campaign\enum;


class CampaignStatus
{
    const LIVE = '1';
    const PAUSED = '2';
    const CANCELLED = '3';
    const DELIVERED = '4';
    const REACTIVATED = '5';
    const SHORTFALL = '6';

    const CAMPAIGN_STATUS = [
        self::LIVE => 'Live',
        self::PAUSED => 'Paused',
        self::CANCELLED => 'Cancelled',
        self::DELIVERED => 'Delivered',
        self::REACTIVATED => 'Reactivated',
        self::SHORTFALL => 'Shortfall',
    ];
}

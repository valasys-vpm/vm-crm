<?php

namespace Modules\Campaign\enum;

class PacingType
{
    const DAILY = 'Daily';
    const WEEKLY = 'Weekly';
    const MONTHLY = 'Monthly';

    const PACING_TYPE = [
        self::DAILY => 'Daily',
        self::WEEKLY => 'Weekly',
        self::MONTHLY => 'Monthly'
    ];
}

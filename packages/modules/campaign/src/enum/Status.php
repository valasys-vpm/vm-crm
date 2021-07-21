<?php


namespace Modules\Campaign\enum;


class Status
{
    const ACTIVE = '1';
    const INACTIVE = '0';

    const STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive'
    ];
}

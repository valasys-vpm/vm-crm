<?php


namespace Modules\User\enum;


class UserStatus
{
    const ACTIVE = '1';
    const INACTIVE = '0';

    const USER_STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive'
    ];
}

<?php


namespace Modules\Role\enum;


class RoleStatus
{
    const ACTIVE = '1';
    const INACTIVE = '0';

    const ROLE_STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive'
    ];
}

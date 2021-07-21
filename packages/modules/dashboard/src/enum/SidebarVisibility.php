<?php


namespace Modules\Dashboard\enum;


class SidebarVisibility
{
    const VISIBLE = '1';
    const INVISIBLE = '0';

    const SIDEBAR_VISIBILITIES = [
        self::VISIBLE => 'Visible',
        self::INVISIBLE => 'Invisible'
    ];
}

<?php

namespace Si6\Base\Enums;

/**
 * @method static static SUPER_ADMIN()
 * @method static static OPERATOR_MANAGER()
 * @method static static OPERATOR()
 * @method static static SUPPORTER()
 * @method static static ACCOUNTING_MANAGER()
 * @method static static ACCOUNTING()
 * @method static static STADIUM_MANAGER()
 * @method static static STADIUM_STAFF()
 */
class AdminRole extends Enum
{
    const SUPER_ADMIN        = 'admin_super_admin';
    const OPERATOR_MANAGER   = 'admin_operator_manager';
    const OPERATOR           = 'admin_operator';
    const SUPPORTER          = 'admin_supporter';
    const ACCOUNTING_MANAGER = 'admin_accounting_manager';
    const ACCOUNTING         = 'admin_accounting';
    const STADIUM_MANAGER    = 'admin_stadium_manager';
    const STADIUM_STAFF      = 'admin_stadium_staff';
}

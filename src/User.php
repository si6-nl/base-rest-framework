<?php

namespace Si6\Base;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Si6\Base\Enums\AdminRole;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'name',
        'email',
        'status',
        'roles',
        'permissions',
        'last_login_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function getPermissions()
    {
        return $this->getAttribute('permissions') ?? [];
    }

    public function isAdmin()
    {
        $roles      = collect($this->roles ?? []);
        $adminRoles = AdminRole::getValues();

        return $roles->intersect($adminRoles)->isNotEmpty();
    }
}

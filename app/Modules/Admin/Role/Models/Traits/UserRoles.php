<?php

namespace App\Modules\Admin\Role\Models\Traits;

use App\Modules\Admin\Role\Models\Role;
use Illuminate\Support\Str;

trait UserRoles
{
    public function roles() {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function canDo($alias, $require = false) {
        if (is_array($alias)) {
            foreach ($alias as $permName) {
                $result = $this->canDo($permName);
                if ($result && !$require) {
                    return true;
                }
                if (!$result && $require) {
                    return false;
                }
            }
        } else {
            foreach ($this->roles as $role) {
                foreach ($role->perms as $perm) {
                    if (Str::is($alias, $perm->alias)) {
                        return true;
                    }
                }
            }
        }
        return $require;
    }

    public function hasRole($alias, $require = false) {
        if (is_array($alias)) {
            foreach ($alias as $roleName) {
                $hasRole = $this->hasRole($roleName);
                if ($hasRole && !$require) {
                    return true;
                }
                if (!$hasRole && $require) {
                    return false;
                }
            }
            return $require;
        }

        foreach ($this->roles as $role) {
            if ($role->alias == $alias) {
                return true;
            }
        }
        return $require;
    }

    public function getMergedPermissions() {
        $result = [];
        foreach ($this->getRoles() as $role) {
            $result = array_merge($result, $role->perms->toArray());
        }
        return $result;
    }

    public function getRoles() {
        $roles = [];
        if ($this->roles) {
            return $this->roles;
        }
        return $roles;
    }
}

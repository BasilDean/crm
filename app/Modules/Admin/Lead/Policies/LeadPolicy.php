<?php

namespace App\Modules\Admin\Lead\Policies;

use App\Modules\Admin\Analytics\Policies\AnalyticPolicy;
use App\Modules\Admin\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization, AnalyticPolicy;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function view(User $user)
    {
        return $user->canDo(['super_admin', 'leads_access', 'dashboard_access']);
    }

    public function create(User $user)
    {
        return $user->canDo(['super_admin', 'leads_create']);
    }

    public function edit(User $user)
    {
        return $user->canDo(['super_admin', 'leads_edit', 'dashboard_access']);
    }

    public function delete(User $user)
    {
        return $user->canDo(['super_admin', 'leads_edit', 'dashboard_access']);
    }

    public function store(User $user)
    {
        return $user->canDo(['super_admin', 'leads_edit']);
    }
}

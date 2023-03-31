<?php

namespace App\Modules\Admin\LeadComment\Policies;

use App\Modules\Admin\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadCommentPolicy
{
    use HandlesAuthorization;

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
        return $user->canDo(['super_admin', 'leads_comment_access', 'dashboard_access']);
    }

    public function create(User $user)
    {
        return $user->canDo(['super_admin', 'leads_comment_access']);
    }

    public function edit(User $user)
    {
        return $user->canDo(['super_admin', 'leads_comment_access']);
    }

    public function delete(User $user)
    {
        return $user->canDo(['super_admin', 'leads_comment_access']);
    }

    public function store(User $user)
    {
        return $user->canDo(['super_admin', 'leads_comment_access']);
    }
}

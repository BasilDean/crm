<?php

namespace App\Modules\Admin\Analytics\Policies;

use App\Modules\Admin\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

trait AnalyticPolicy
{
    public function viewAnalytic(User $user)
    {
        return $user->canDo(['super_admin', 'analytic_access']);
    }
}

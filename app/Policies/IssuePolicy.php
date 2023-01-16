<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IssuePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Allow everything if the user is admin.
     *
     * @param  User  $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Check if the user is the author of the post.
     *
     * @param  User  $user
     * @param  Issue  $issue
     * @return bool
     */
    public function show(User $user, Issue $issue)
    {
        return $issue->user->is($user);
    }
}

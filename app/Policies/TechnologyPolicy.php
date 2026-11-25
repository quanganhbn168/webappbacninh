<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Technology;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechnologyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Technology');
    }

    public function view(AuthUser $authUser, Technology $technology): bool
    {
        return $authUser->can('View:Technology');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Technology');
    }

    public function update(AuthUser $authUser, Technology $technology): bool
    {
        return $authUser->can('Update:Technology');
    }

    public function delete(AuthUser $authUser, Technology $technology): bool
    {
        return $authUser->can('Delete:Technology');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Technology');
    }

    public function restore(AuthUser $authUser, Technology $technology): bool
    {
        return $authUser->can('Restore:Technology');
    }

    public function forceDelete(AuthUser $authUser, Technology $technology): bool
    {
        return $authUser->can('ForceDelete:Technology');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Technology');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Technology');
    }

    public function replicate(AuthUser $authUser, Technology $technology): bool
    {
        return $authUser->can('Replicate:Technology');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Technology');
    }

}

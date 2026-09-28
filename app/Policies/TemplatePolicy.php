<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Template;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Template');
    }

    public function view(AuthUser $authUser, Template $template): bool
    {
        return $authUser->can('View:Template');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Template');
    }

    public function update(AuthUser $authUser, Template $template): bool
    {
        return $authUser->can('Update:Template');
    }

    public function delete(AuthUser $authUser, Template $template): bool
    {
        return $authUser->can('Delete:Template');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Template');
    }

    public function restore(AuthUser $authUser, Template $template): bool
    {
        return $authUser->can('Restore:Template');
    }

    public function forceDelete(AuthUser $authUser, Template $template): bool
    {
        return $authUser->can('ForceDelete:Template');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Template');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Template');
    }

    public function replicate(AuthUser $authUser, Template $template): bool
    {
        return $authUser->can('Replicate:Template');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Template');
    }

}

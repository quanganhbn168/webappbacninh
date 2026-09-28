<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MiniApp;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MiniAppPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MiniApp');
    }

    public function view(AuthUser $authUser, MiniApp $miniApp): bool
    {
        return $authUser->can('View:MiniApp');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MiniApp');
    }

    public function update(AuthUser $authUser, MiniApp $miniApp): bool
    {
        return $authUser->can('Update:MiniApp');
    }

    public function delete(AuthUser $authUser, MiniApp $miniApp): bool
    {
        return $authUser->can('Delete:MiniApp');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MiniApp');
    }

    public function restore(AuthUser $authUser, MiniApp $miniApp): bool
    {
        return $authUser->can('Restore:MiniApp');
    }

    public function forceDelete(AuthUser $authUser, MiniApp $miniApp): bool
    {
        return $authUser->can('ForceDelete:MiniApp');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MiniApp');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MiniApp');
    }

    public function replicate(AuthUser $authUser, MiniApp $miniApp): bool
    {
        return $authUser->can('Replicate:MiniApp');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MiniApp');
    }
}

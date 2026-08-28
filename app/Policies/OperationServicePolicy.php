<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\OperationService;
use Illuminate\Auth\Access\HandlesAuthorization;

class OperationServicePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:OperationService');
    }

    public function view(AuthUser $authUser, OperationService $operationService): bool
    {
        return $authUser->can('View:OperationService');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:OperationService');
    }

    public function update(AuthUser $authUser, OperationService $operationService): bool
    {
        return $authUser->can('Update:OperationService');
    }

    public function delete(AuthUser $authUser, OperationService $operationService): bool
    {
        return $authUser->can('Delete:OperationService');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:OperationService');
    }

    public function restore(AuthUser $authUser, OperationService $operationService): bool
    {
        return $authUser->can('Restore:OperationService');
    }

    public function forceDelete(AuthUser $authUser, OperationService $operationService): bool
    {
        return $authUser->can('ForceDelete:OperationService');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:OperationService');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:OperationService');
    }

    public function replicate(AuthUser $authUser, OperationService $operationService): bool
    {
        return $authUser->can('Replicate:OperationService');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:OperationService');
    }

}

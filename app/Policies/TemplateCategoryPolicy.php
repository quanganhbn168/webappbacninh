<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TemplateCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplateCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TemplateCategory');
    }

    public function view(AuthUser $authUser, TemplateCategory $templateCategory): bool
    {
        return $authUser->can('View:TemplateCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TemplateCategory');
    }

    public function update(AuthUser $authUser, TemplateCategory $templateCategory): bool
    {
        return $authUser->can('Update:TemplateCategory');
    }

    public function delete(AuthUser $authUser, TemplateCategory $templateCategory): bool
    {
        return $authUser->can('Delete:TemplateCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TemplateCategory');
    }

    public function restore(AuthUser $authUser, TemplateCategory $templateCategory): bool
    {
        return $authUser->can('Restore:TemplateCategory');
    }

    public function forceDelete(AuthUser $authUser, TemplateCategory $templateCategory): bool
    {
        return $authUser->can('ForceDelete:TemplateCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TemplateCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TemplateCategory');
    }

    public function replicate(AuthUser $authUser, TemplateCategory $templateCategory): bool
    {
        return $authUser->can('Replicate:TemplateCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TemplateCategory');
    }

}

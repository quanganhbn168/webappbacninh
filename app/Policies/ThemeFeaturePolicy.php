<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ThemeFeature;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThemeFeaturePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ThemeFeature');
    }

    public function view(AuthUser $authUser, ThemeFeature $themeFeature): bool
    {
        return $authUser->can('View:ThemeFeature');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ThemeFeature');
    }

    public function update(AuthUser $authUser, ThemeFeature $themeFeature): bool
    {
        return $authUser->can('Update:ThemeFeature');
    }

    public function delete(AuthUser $authUser, ThemeFeature $themeFeature): bool
    {
        return $authUser->can('Delete:ThemeFeature');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ThemeFeature');
    }

    public function restore(AuthUser $authUser, ThemeFeature $themeFeature): bool
    {
        return $authUser->can('Restore:ThemeFeature');
    }

    public function forceDelete(AuthUser $authUser, ThemeFeature $themeFeature): bool
    {
        return $authUser->can('ForceDelete:ThemeFeature');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ThemeFeature');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ThemeFeature');
    }

    public function replicate(AuthUser $authUser, ThemeFeature $themeFeature): bool
    {
        return $authUser->can('Replicate:ThemeFeature');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ThemeFeature');
    }

}

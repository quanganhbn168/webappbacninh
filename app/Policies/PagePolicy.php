<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Page;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PagePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Page');
    }

    public function view(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('View:Page');
    }

    public function create(AuthUser $authUser): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function update(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('Update:Page');
    }

    public function delete(AuthUser $authUser, Page $page): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function restore(AuthUser $authUser, Page $page): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function forceDelete(AuthUser $authUser, Page $page): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function replicate(AuthUser $authUser, Page $page): bool
    {
        return false; // Pages are fixed in code (App\Domain\Pages\SitePages).
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Page');
    }
}

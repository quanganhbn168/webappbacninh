<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Component;

/** Own name, email and password, from the menu under the avatar. */
class EditProfile extends BaseEditProfile
{
    protected function getNameFormComponent(): Component
    {
        return parent::getNameFormComponent()->helperText('Tên này hiện ở dòng "Tác giả" của các bài bạn viết.');
    }
}

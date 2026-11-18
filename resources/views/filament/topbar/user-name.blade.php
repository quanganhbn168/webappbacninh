{{-- Name and role beside the avatar of the user menu. --}}
@php
    $user = filament()->auth()->user();
    $roles = ['super_admin' => 'Quản trị cấp cao', 'admin' => 'Quản trị viên', 'editor' => 'Biên tập viên'];
    $role = $user?->roles->first()?->name;
@endphp
@if ($user)
    <div class="-ms-2 hidden flex-col leading-tight sm:flex">
        <span class="text-sm font-semibold text-gray-950 dark:text-white">{{ $user->name }}</span>
        @if ($role)
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $roles[$role] ?? \Illuminate\Support\Str::headline($role) }}</span>
        @endif
    </div>
@endif

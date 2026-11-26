<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Creates the admin permissions and an administrator. No password lives in the code: it is
 * typed when asked, passed with --password, or generated and shown once.
 */
class SetupAdminCommand extends Command
{
    protected $signature = 'app:setup-admin
        {--email= : Email đăng nhập}
        {--password= : Mật khẩu, chỉ đặt khi tạo mới tài khoản}
        {--name=Quản trị viên : Tên hiển thị}';

    protected $description = 'Sinh quyền Filament Shield, tạo tài khoản quản trị và gán vai trò super_admin. Chạy lại nhiều lần vẫn an toàn.';

    public function handle(): int
    {
        $this->components->task('Sinh quyền cho trang quản trị', fn (): bool => $this->callSilently('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--option' => 'permissions',
            '--no-interaction' => true,
        ]) === self::SUCCESS);

        $email = (string) ($this->option('email') ?: $this->ask('Email đăng nhập'));

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->components->error('Cần một email hợp lệ, ví dụ: php artisan app:setup-admin --email=ban@congty.vn');

            return self::FAILURE;
        }

        $user = User::query()->where('email', $email)->first();
        $generated = null;

        if (! $user) {
            $password = (string) ($this->option('password') ?: ($this->input->isInteractive() ? $this->secret('Mật khẩu (bỏ trống để tự sinh)') : ''));

            if ($password === '') {
                $password = $generated = Str::password(16, symbols: false);
            }

            if (mb_strlen($password) < 8) {
                $this->components->error('Mật khẩu cần ít nhất 8 ký tự.');

                return self::FAILURE;
            }

            $user = User::query()->create(['name' => (string) $this->option('name'), 'email' => $email, 'password' => $password]);
        }

        $role = Role::findOrCreate(config('filament-shield.super_admin.name', 'super_admin'), 'admin');
        $role->syncPermissions(Permission::query()->where('guard_name', 'admin')->get());
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $user->assignRole($role);

        $this->components->info($user->wasRecentlyCreated
            ? "Đã tạo {$email} và gán super_admin."
            : "{$email} đã tồn tại, giữ nguyên mật khẩu, đã đảm bảo có vai trò super_admin.");

        if ($generated !== null) {
            $this->components->warn("Mật khẩu (chỉ hiện lần này): {$generated}");
        }

        return self::SUCCESS;
    }
}

<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Continue after the highest number already in use, e.g. DA-007 → 8.
        $highest = DB::table('projects')->pluck('code')
            ->map(fn (?string $code): int => preg_match('/(\d+)\D*$/', (string) $code, $match) ? (int) $match[1] : 0)
            ->max() ?? 0;

        $this->migrator->add('project.code_prefix', 'DA-');
        $this->migrator->add('project.code_suffix', '');
        $this->migrator->add('project.code_digits', 3);
        $this->migrator->add('project.code_next', $highest + 1);
        $this->migrator->add('project.deliverable_presets', [
            'Trang chủ',
            'Giới thiệu',
            'Dịch vụ',
            'Sản phẩm',
            'Chi tiết sản phẩm',
            'Giỏ hàng',
            'Đặt hàng',
            'Dự án',
            'Tin tức',
            'Liên hệ',
            'Form tư vấn',
            'Quản trị nội dung',
            'Quản lý đơn hàng',
            'Giao diện responsive',
            'SEO nền tảng',
            'Hướng dẫn sử dụng',
        ]);
    }

    public function down(): void
    {
        foreach (['code_prefix', 'code_suffix', 'code_digits', 'code_next', 'deliverable_presets'] as $name) {
            $this->migrator->deleteIfExists('project.'.$name);
        }
    }
};

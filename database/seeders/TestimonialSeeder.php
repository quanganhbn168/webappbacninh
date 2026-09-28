<?php

namespace Database\Seeders;

use App\Domain\Media\Actions\ImportLocalImage;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Sample testimonials from the approved design; replace them with real customer feedback in the admin.
     */
    public function run(ImportLocalImage $images): void
    {
        foreach ($this->testimonials() as $order => $row) {
            if (Testimonial::query()->where('name', $row['name'])->exists()) {
                continue;
            }

            Testimonial::query()->create([
                'name' => $row['name'],
                'role' => $row['role'],
                'quote' => $row['quote'],
                'avatar_id' => $images->execute('frontend/images/'.$row['avatar'], 'testimonials', $row['name'])?->id,
                'rating' => 5,
                'order' => $order + 1,
                'is_active' => true,
            ]);
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function testimonials(): array
    {
        return [
            ['name' => 'Anh Nguyễn Văn Hùng', 'role' => 'Giám đốc - Hòa Bình Resort', 'avatar' => 'avatar-hung.svg',
                'quote' => 'Đội ngũ chuyên nghiệp, hỗ trợ rất nhiệt tình. Website hoạt động ổn định và giúp chúng tôi tăng nhiều khách hàng mới.'],
            ['name' => 'Chị Trần Thị Mai', 'role' => 'CEO - Thời trang May', 'avatar' => 'avatar-mai.svg',
                'quote' => 'Giải pháp CRM rất phù hợp với quy trình của chúng tôi. Tiết kiệm nhiều thời gian và chi phí vận hành.'],
            ['name' => 'Anh Lê Minh Tuấn', 'role' => 'Giám đốc - Nội thất An Phát', 'avatar' => 'avatar-tuan.svg',
                'quote' => 'Làm việc nhanh, đúng tiến độ, chất lượng vượt mong đợi. Rất yên tâm khi hợp tác lâu dài.'],
        ];
    }
}

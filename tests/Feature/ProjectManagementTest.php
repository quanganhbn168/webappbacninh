<?php

namespace Tests\Feature;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Models\ProjectCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_created_project_is_listed_with_its_group_and_has_a_detail_page(): void
    {
        $suffix = Str::lower(Str::random(6));
        $group = ProjectCategory::query()->create(['name' => 'Nhóm thử '.$suffix, 'slug' => 'nhom-thu-'.$suffix]);

        Livewire::actingAs($this->admin(), 'admin')->test(CreateProject::class)
            ->fillForm([
                'title' => 'Dự án thử '.$suffix,
                'slug' => 'du-an-thu-'.$suffix,
                'code' => 'DA-'.$suffix,
                'project_category_id' => $group->id,
                'excerpt' => 'Website cho nhà hàng.',
                'client' => 'Nhà hàng Bắc Ninh',
                'industry' => 'Ẩm thực',
                'results' => [['value' => 'Tăng đơn đặt bàn']],
                'deliverables' => ['Trang chủ', 'Thực đơn'],
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get(route('projects.index'))->assertOk()
            ->assertSee('Dự án thử '.$suffix)
            ->assertSee('data-filter="nhom-thu-'.$suffix.'"', false);

        $this->get(route('projects.show', 'du-an-thu-'.$suffix))->assertOk()
            ->assertSee('Nhà hàng Bắc Ninh')
            ->assertSee('Ẩm thực')
            ->assertSee('Tăng đơn đặt bàn')
            ->assertSee('Thực đơn');
    }

    public function test_admin_lists_render(): void
    {
        foreach (['projects', 'project-categories'] as $path) {
            $this->actingAs($this->admin(), 'admin')->get('/admin/'.$path)->assertOk();
        }
    }

    private function admin(): User
    {
        return User::query()->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))->firstOrFail();
    }
}

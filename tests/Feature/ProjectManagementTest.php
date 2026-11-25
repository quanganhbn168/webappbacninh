<?php

namespace Tests\Feature;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Technologies\Pages\ManageTechnologies;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
use App\Models\TemplateCategory;
use App\Models\User;
use App\Settings\ProjectSettings;
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

        $food = TemplateCategory::findOrCreateByName('Ẩm thực '.$suffix);
        $travel = TemplateCategory::findOrCreateByName('Lữ hành '.$suffix);
        $laravel = Technology::findOrCreateByName('Laravel');

        Livewire::actingAs($this->admin(), 'admin')->test(CreateProject::class)
            ->fillForm([
                'title' => 'Dự án thử '.$suffix,
                'slug' => 'du-an-thu-'.$suffix,
                'code' => 'DA-'.$suffix,
                'project_category_id' => $group->id,
                'excerpt' => 'Website cho nhà hàng.',
                'client' => 'Nhà hàng Bắc Ninh',
                'industries' => [$food->id, $travel->id],
                'technologies' => [$laravel->id],
                'results' => [['value' => 'Tăng đơn đặt bàn']],
                'deliverable_presets' => ['Trang chủ'],
                'deliverable_extra' => ['Thực đơn'],
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get(route('projects.index'))->assertOk()
            ->assertSee('Dự án thử '.$suffix)
            ->assertSee('data-catalog-filter="nhom-thu-'.$suffix.'"', false);

        $this->get(route('projects.show', 'du-an-thu-'.$suffix))->assertOk()
            ->assertSee('Nhà hàng Bắc Ninh')
            ->assertSee('Ẩm thực '.$suffix)
            ->assertSee('Lữ hành '.$suffix)
            ->assertSee('<span>Laravel</span>', false)
            ->assertSee('data-project-gallery', false)
            ->assertSee('Tăng đơn đặt bàn')
            ->assertSee('Thực đơn');

        $project = Project::query()->where('slug', 'du-an-thu-'.$suffix)->firstOrFail();
        $this->assertSame(['Trang chủ', 'Thực đơn'], $project->deliverables);

        // Editing shows ticked and typed deliverables apart, and keeps both.
        Livewire::actingAs($this->admin(), 'admin')->test(EditProject::class, ['record' => $project->id])
            ->assertSchemaStateSet(['deliverable_presets' => ['Trang chủ'], 'deliverable_extra' => ['Thực đơn']])
            ->fillForm(['deliverable_presets' => ['Liên hệ', 'Trang chủ']])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(['Trang chủ', 'Liên hệ', 'Thực đơn'], $project->refresh()->deliverables);
        $this->assertCount(2, $project->industries);
        $this->assertCount(1, $project->technologies);
    }

    public function test_codes_follow_the_configured_prefix_suffix_and_next_number(): void
    {
        $group = ProjectCategory::query()->firstOrCreate(['slug' => 'nhom-ma'], ['name' => 'Nhóm mã']);
        $create = fn (?string $code = null): Project => Project::query()->create([
            'title' => 'Dự án mã '.Str::random(8), 'project_category_id' => $group->id, 'code' => $code,
        ]);

        Livewire::actingAs($this->admin(), 'admin')->test(ListProjects::class)
            ->callAction('projectSettings', [
                'code_prefix' => 'WB-', 'code_next' => 49, 'code_digits' => 3, 'code_suffix' => '-X',
                'deliverable_presets' => ['Trang chủ'],
            ])
            ->assertHasNoActionErrors();

        $create('WB-050-X');

        $this->assertSame('WB-049-X', $create()->code);
        // 050 is taken by hand, so it is skipped.
        $this->assertSame('WB-051-X', $create()->code);
        $this->assertSame('TAY-1', $create('TAY-1')->code);
        $this->assertSame(52, app(ProjectSettings::class)->refresh()->code_next);
    }

    public function test_project_images_are_served_as_webp_sized_for_the_frame(): void
    {
        $project = Project::query()->where('slug', 'website-doanh-nghiep-san-xuat')->firstOrFail();
        $media = $project->gallery_media->first();

        $slide = $project->slides[0];
        $this->assertStringContainsString('fm=webp', $slide['src']);
        $this->assertStringContainsString(' 640w', $slide['srcset']);
        // No size is larger than the uploaded original.
        $this->assertStringContainsString(' '.min($media->width, 1920).'w', $slide['srcset']);
        $this->assertStringNotContainsString(' '.($media->width + 1).'w', $slide['srcset']);

        $this->get($slide['thumb'])->assertOk()->assertHeader('Content-Type', 'image/webp');
        $this->get(route('projects.show', $project->slug))->assertOk()->assertSee('srcset=', false);
    }

    public function test_the_site_name_is_added_to_the_seo_title_once(): void
    {
        $project = Project::query()->where('slug', 'website-doanh-nghiep-san-xuat')->firstOrFail();
        $name = site_config('name');
        $title = fn (): string => '<title>'.e($this->get(route('projects.show', $project->slug))->viewData('pageTitle')).'</title>';

        $project->update(['meta_title' => null]);
        $this->assertSame('<title>'.e($project->title.' | '.$name).'</title>', $title());

        $project->update(['meta_title' => 'Tiêu đề riêng']);
        $this->assertSame('<title>'.e('Tiêu đề riêng | '.$name).'</title>', $title());

        // Titles typed the old way, with the name, do not get it twice.
        $project->update(['meta_title' => 'Tiêu đề cũ | '.$name]);
        $this->assertSame('<title>'.e('Tiêu đề cũ | '.$name).'</title>', $title());
    }

    public function test_projects_can_be_shown_and_featured_from_the_list(): void
    {
        $project = Project::query()->where('slug', 'website-doanh-nghiep-san-xuat')->firstOrFail();

        Livewire::actingAs($this->admin(), 'admin')->test(ListProjects::class)
            ->assertTableColumnDoesNotExist('year')
            ->call('updateTableColumnState', 'is_active', (string) $project->id, false);

        $this->assertFalse($project->refresh()->is_active);
    }

    public function test_technology_page_renders(): void
    {
        Livewire::actingAs($this->admin(), 'admin')->test(ManageTechnologies::class)
            ->assertOk()
            ->assertCanSeeTableRecords(Technology::query()->whereIn('name', ['PHP', 'Tailwind CSS'])->get())
            // The logo survives the table's HTML sanitizer.
            ->assertSee('src="data:image/svg', false)
            ->callAction('create', ['name' => 'Công nghệ thử', 'icon' => 'brand-php'])
            ->assertHasNoActionErrors();

        // A new entry goes to the end of the list.
        $this->assertSame('Công nghệ thử', Technology::query()->orderByDesc('order')->value('name'));
    }

    public function test_project_story_keeps_rich_formatting_and_hides_blank_or_unsafe_content(): void
    {
        $suffix = Str::lower(Str::random(6));
        $group = ProjectCategory::query()->create(['name' => 'Nhóm thử '.$suffix, 'slug' => 'nhom-thu-'.$suffix]);

        Livewire::actingAs($this->admin(), 'admin')->test(CreateProject::class)
            ->fillForm([
                'title' => 'Dự án rich '.$suffix,
                'slug' => 'du-an-rich-'.$suffix,
                'project_category_id' => $group->id,
                'excerpt' => 'Website du lịch.',
                'challenge' => '<h2 style="text-align: center">Khó khăn</h2>'
                    .'<p><strong>Tour phân tán</strong> <span class="color" data-color="red">gấp</span> <mark>chú ý</mark></p>'
                    .'<ul><li><p>Ý một</p></li></ul>'
                    .'<table><tbody><tr><th><p>Cột</p></th></tr><tr><td><p>Ô</p></td></tr></tbody></table>'
                    .'<details><summary>Xem thêm</summary><div data-type="detailsContent"><p>Chi tiết</p></div></details>'
                    .'<div class="grid-layout" data-cols="2" data-from-breakpoint="lg"><div class="grid-layout-col" data-col-span="1"><p>Trái</p></div><div class="grid-layout-col" data-col-span="1"><p>Phải</p></div></div>'
                    .'<p><a href="javascript:alert(1)">liên kết xấu</a></p>',
                'solution' => '<p></p>',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->get(route('projects.show', 'du-an-rich-'.$suffix))->assertOk()
            ->assertSee('<strong>Tour phân tán</strong>', false)
            ->assertSee('text-align: center', false)
            ->assertSee('--color:', false)
            ->assertSee('<mark>chú ý</mark>', false)
            ->assertSee('<table', false)
            ->assertSee('<summary>Xem thêm</summary>', false)
            ->assertSee('class="collapse show" id="story-challenge"', false)
            ->assertDontSee('id="story-solution"', false)
            ->assertSee('grid-layout-col', false)
            ->assertDontSee('javascript:alert', false)
            ->assertDontSee('Giải pháp triển khai');
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

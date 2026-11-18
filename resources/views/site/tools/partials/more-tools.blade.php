@php($otherTools = \App\Models\MiniApp::query()->active()->ordered()->get()->reject(fn ($tool) => url($tool->link) === request()->url())->take(4))
@if ($otherTools->isNotEmpty())
    <section class="section section--soft">
        <div class="container">
            <x-section-head title="Công cụ miễn phí khác" :href="route('tools.index')" link="Xem tất cả công cụ" />
            <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                @foreach ($otherTools as $tool)
                    <div class="col"><x-feature-card :icon="$tool->icon ?: 'wrench'" :title="$tool->name" :text="$tool->description" :href="url($tool->link)" /></div>
                @endforeach
            </div>
        </div>
    </section>
@endif

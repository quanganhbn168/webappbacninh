{{-- SEO score of the article being written: asks the page for a new score shortly after any change. --}}
<div
    class="seo-panel"
    wire:ignore
    x-data="{
        report: null, busy: false, again: false, failed: false,
        async run() {
            if (this.busy) { this.again = true; return }
            this.busy = true
            try { this.report = await $wire.analyzeSeo(); this.failed = false }
            catch (error) { this.failed = true }
            finally {
                this.busy = false
                if (this.again) { this.again = false; this.run() }
            }
        },
    }"
    x-init="$nextTick(() => run())"
    x-on:input.window.debounce.1500ms="run()"
    x-on:change.window.debounce.1500ms="run()"
    x-on:click.window.debounce.2500ms="if (! $root.contains($event.target)) run()"
>
    <p class="seo-panel__note" x-show="! report && ! failed">Đang chấm điểm…</p>
    <p class="seo-panel__note" x-show="failed" x-cloak>Chưa chấm được. Thử bấm "Chấm lại".</p>

    <template x-if="report">
        <div class="seo-panel__report">
            <div class="seo-panel__score" :data-grade="report.grade">
                <strong x-text="report.score"></strong>
                <div>
                    <span x-text="report.verdict"></span>
                    <small>Đạt chuẩn: 100/100, không còn chấm đỏ hay cam</small>
                    <small>Mục tính điểm theo tài liệu Google; thang điểm do website đặt, Google không chấm điểm.</small>
                </div>
            </div>

            <div class="seo-panel__preview" aria-label="Xem trước trên Google">
                <small x-text="report.preview.url"></small>
                <strong x-text="report.preview.title"></strong>
                <p x-text="report.preview.description"></p>
            </div>

            <template x-for="group in report.groups" :key="group.title">
                <details class="seo-panel__group" x-init="$el.open = group.problems > 0 && ! group.advice">
                    <summary>
                        <span x-text="group.title"></span>
                        <span class="seo-panel__count" :data-clean="group.problems === 0 || group.advice" x-text="group.advice ? group.problems + ' gợi ý' : (group.problems === 0 ? 'Đạt' : group.problems + ' cần sửa')"></span>
                    </summary>
                    <ul>
                        <template x-for="check in group.checks" :key="check.key">
                            <li :data-status="check.status">
                                <span x-text="check.message"></span>
                                <template x-if="check.source">
                                    <a :href="check.source" target="_blank" rel="noopener noreferrer">Google ↗</a>
                                </template>
                            </li>
                        </template>
                    </ul>
                </details>
            </template>
        </div>
    </template>

    <button type="button" class="seo-panel__again" x-on:click="run()" :disabled="busy" x-text="busy ? 'Đang chấm…' : 'Chấm lại'"></button>
</div>

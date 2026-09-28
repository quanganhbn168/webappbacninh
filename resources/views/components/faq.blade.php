{{-- Questions as ['q' => …, 'a' => …] or [question, answer]; one open at a time (details[name]). --}}
@props(['items' => [], 'columns' => 2, 'name' => 'faq'])
<div {{ $attributes->class(['faq', 'faq--two' => $columns === 2]) }}>
    @foreach ($items as $item)
        <details class="faq__item" name="{{ $name }}" @if ($loop->first && $columns === 1) open @endif>
            <summary>{{ $item['q'] ?? $item[0] }} <x-icon name="chevron-down" /></summary>
            <div class="faq__answer">{{ $item['a'] ?? $item[1] }}</div>
        </details>
    @endforeach
</div>

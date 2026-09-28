@props(['name', 'label' => null])
{{ \App\Support\Icons::svg($name, $attributes->get('class', ''), $label) }}

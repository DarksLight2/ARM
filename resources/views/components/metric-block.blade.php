@props([
    'title',
    'value',
    'hint',
])
<div {{ $attributes->merge(['class' => 'rounded-2xl bg-black/30 p-4 ring-1 ring-white/10 backdrop-blur']) }}>
    <div class="text-xs font-semibold uppercase tracking-wide text-white/50">{{ $title }}</div>
    <div class="mt-2 text-3xl font-semibold text-white/90">{{ $value }}</div>
    <div class="mt-2 text-xs text-white/50">{{ $hint }}</div>
</div>

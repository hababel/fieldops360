@props(['title' => '-', 'value' => 0, 'hint' => null])

<div class="bg-white shadow-sm sm:rounded-lg p-5">
    <div class="text-sm text-gray-500">{{ $title }}</div>
    <div class="mt-2 text-3xl font-bold text-gray-800">{{ $value }}</div>
    @if($hint)
        <div class="mt-1 text-xs text-gray-400">{{ $hint }}</div>
    @endif
</div>

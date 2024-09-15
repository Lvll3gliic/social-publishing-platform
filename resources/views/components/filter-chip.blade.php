@props(['filter', 'href' => '#'])

<a
    href="{{ $href }}"
    class="rounded-md py-0.5 px-2.5 border border-transparent text-xs text-white transition-all shadow-sm cursor-pointer bg-slate-600"
>
    {{ $filter }}
</a>

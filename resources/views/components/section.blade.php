@props(['height', 'title'])

<section class="p-5 h-full w-full overflow-x-hidden">
    <div
        class="flex rounded-[12px] flex-col bg-white text-[#334155] shadow font-sans text-[1rem] box-border h-full overflow-y-scroll overflow-x-hidden">
        <div class="flex flex-col gap-1 p-6 box-border">
            @if (isset($title))
            <div class="flex flex-col gap-0.5">
                <div class="flex flex-col gap-0.5 mb-0 text-[1.25rem] font-[600]">{{ $title }}</div>
            </div>
            @endif
            <div class="p-0">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
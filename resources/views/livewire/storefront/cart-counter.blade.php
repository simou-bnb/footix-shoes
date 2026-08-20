<a href="{{ route('cart') }}" wire:navigate class="relative inline-flex items-center justify-center w-10 h-10 text-black">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.847-4.652 2.152-7.043.107-.838-.517-1.579-1.362-1.579H5.106M7.5 14.25L5.106 5.214M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
    </svg>
    @if ($count > 0)
        <span class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-[11px] font-bold text-white bg-black rounded-full">
            {{ $count }}
        </span>
    @endif
</a>

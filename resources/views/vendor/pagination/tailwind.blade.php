@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center py-6">
        <div class="flex flex-wrap items-center justify-center gap-2">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="inline-flex size-10 items-center justify-center rounded-lg border border-luxury-border/40 bg-luxury-surface/50 text-luxury-secondary/40 cursor-not-allowed select-none">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex size-10 items-center justify-center rounded-lg border border-luxury-border bg-luxury-surface text-luxury-primary shadow-xs transition-all duration-200 hover:border-luxury-gold hover:text-luxury-gold active:scale-95" aria-label="{{ __('pagination.previous') }}">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="inline-flex size-10 items-center justify-center rounded-lg border border-luxury-border/40 bg-luxury-surface/40 text-xs font-semibold text-luxury-secondary/50 cursor-default select-none">
                            {{ $element }}
                        </span>
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="inline-flex size-10 items-center justify-center rounded-lg border border-luxury-gold bg-luxury-gold text-xs font-bold text-luxury-bg shadow-md cursor-default select-none">
                                    {{ $page }}
                                </span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex size-10 items-center justify-center rounded-lg border border-luxury-border bg-luxury-surface text-xs font-semibold text-luxury-secondary shadow-xs transition-all duration-200 hover:border-luxury-gold hover:text-luxury-gold active:scale-95" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex size-10 items-center justify-center rounded-lg border border-luxury-border bg-luxury-surface text-luxury-primary shadow-xs transition-all duration-200 hover:border-luxury-gold hover:text-luxury-gold active:scale-95" aria-label="{{ __('pagination.next') }}">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="inline-flex size-10 items-center justify-center rounded-lg border border-luxury-border/40 bg-luxury-surface/50 text-luxury-secondary/40 cursor-not-allowed select-none">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </span>
            @endif

        </div>
    </nav>
@endif

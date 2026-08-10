@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center py-6">
        <div class="flex items-center justify-center gap-2">
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

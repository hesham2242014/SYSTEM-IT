@if ($paginator->hasPages())
    <nav class="pagination">
        @if ($paginator->onFirstPage())
            <span class="disabled"><span>السابق</span></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">السابق</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="disabled"><span>{{ $element }}</span></span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active"><span>{{ $page }}</span></span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">التالي</a>
        @else
            <span class="disabled"><span>التالي</span></span>
        @endif
    </nav>
@endif

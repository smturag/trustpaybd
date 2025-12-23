<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
    <ul class="pagination">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <li class="paginate_button page-item previous disabled" id="example2_previous">
                <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">Prev</a>
            </li>
        @else
            <li class="paginate_button page-item previous" id="example2_previous">
                <a href="{{ $paginator->previousPageUrl() }}" aria-controls="example2" data-dt-idx="0" tabindex="0"
                    class="page-link">Prev</a>
            </li>
        @endif

        <!-- Pagination Elements -->
        @foreach ($paginator->links()->elements as $element)
            @if (is_string($element))
                <li class="paginate_button page-item disabled">
                    <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0"
                        class="page-link">{{ $element }}</a>
                </li>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="paginate_button page-item active">
                            <a href="#" aria-controls="example2" data-dt-idx="{{ $page }}" tabindex="0"
                                class="page-link">{{ $page }}</a>
                        </li>
                    @else
                        <li class="paginate_button page-item">
                            <a href="{{ $url }}" aria-controls="example2" data-dt-idx="{{ $page }}"
                                tabindex="0" class="page-link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <li class="paginate_button page-item next" id="example2_next">
                <a href="{{ $paginator->nextPageUrl() }}" aria-controls="example2" data-dt-idx="7" tabindex="0"
                    class="page-link">Next</a>
            </li>
        @else
            <li class="paginate_button page-item next disabled" id="example2_next">
                <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
            </li>
        @endif
    </ul>
</div>

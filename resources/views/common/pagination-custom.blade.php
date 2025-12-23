<div class="row align-items-center mt-3 mb-3">
{{--$login_logs->links('includes.pagination-custom')--}}
{{--{!! $data->withPath('/admin/balance_manager')->links('admin.layout.pagination-custom') !!}--}}
{{--{{ $posts->withQueryString()->links() }}--}}

@if ($paginator->hasPages())
    {{--<div>Showing {{($paginator->currentpage()-1)*$paginator->perpage()+1}} to {{$paginator->currentpage()*$paginator->perpage()}} of {{$paginator->total()}} entries</div>--}}
    <div class="col-sm-12 col-md-5">Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of total {{$paginator->total()}} entries</div>

    <nav aria-label="Page navigation" class="col-sm-12 col-md-7">
        <ul class="pagination justify-content-end m-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">First</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1"><i class="bx bx-chevron-left"></i></a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ url()->current() . '?page=1' }}">First</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="bx bx-chevron-left fa-sm"></i></a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">{{ $element }}</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="bx bx-chevron-right fa-sm"></i></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ url()->current() . '?page='. $paginator->lastPage() }}" rel="next">Last</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#"><i class="bx bx-chevron-right fa-sm"></i></a>
                </li>
            @endif
        </ul>
    </nav>
@endif
</div>

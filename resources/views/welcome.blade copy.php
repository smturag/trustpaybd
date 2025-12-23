<!DOCTYPE html>
<html lang="en">

<head>
	@include('partial.customer_head')
</head>

<body class="container-page send-money request-page">
    <div id="scroll-top-area">
        <a href="index.html#top-header"><i class="ti-angle-double-up" aria-hidden="true"></i></a>
    </div>
	{{-- start navbar --}}
	@include('partial.customer_navbar')
	{{-- end navbar --}}
	{{-- <x-dashboard /> --}}
	{{-- @include('pertial.customer_dashboard') --}}

        @yield('customer')

	{{-- start content --}}



	{{-- end content --}}
{{-- customer_script start --}}


@include('partial.customer_footer')
@include('partial.customer_script')
{{-- customer_script end --}}

</body>

</html>


@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">

            {{ session('message') }}

        </div>
    @endif


    @if (session()->has('alert'))
        <div class="alert alert-warning" id="alert_warning">

            {{ session('alert') }}

        </div>
    @endif
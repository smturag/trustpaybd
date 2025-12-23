@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')


@section('content')

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    @if(session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{session('message')}}
        </div>
    @endif

    @if (Session::has('alert'))

        <div class="alert alert-danger">{{ Session::get('alert') }}</div>

    @endif


    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Create MobCash USer</h5>

                    <form class="row g-3" action="{{ route('mcuserAddAction') }}" method="POST">

                        @csrf

                        <div class="col-md-12">

                            <label for="userid" class="form-label">Userid</label>

                            <input type="tel" name="userid" class="form-control @error('userid') is-invalid @enderror" id="userid" placeholder="Userid">

                            @error('userid')

                            <div class="alert alert-danger mt-2">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="col-md-12">

                            <label for="password" class="form-label">Password</label>

                            <input type="text" onkeyup="notspace(this);" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="password">

                            @error('password')

                            <div class="alert alert-danger mt-2">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="col-md-12">

                            <label for="workcode" onkeyup="notspace(this);" class="form-label">Workcode</label>

                            <input type="tel" name="workcode" class="form-control @error('workcode') is-invalid @enderror" id="workcode" placeholder="workcode">

                            @error('workcode')

                            <div class="alert alert-danger mt-2">{{ $message }}</div>

                            @enderror

                        </div>


                        <div class="col-md-12">
                        <div class="form-group">
                            <label for="currency">{{ translate('currency') }}</label>
                            <select class="form-control" name="currency" id="currency">
                                <option value="USD">USD</option>
                                <option value="BDT">BDT</option>
                                <option value="INR">INR</option>
                               
                                
                            </select>
                        </div>
                        </div>


                        <div class="col-md-12">

                            <label for="password" class="form-label">Location</label>

                            <textarea class="form-control" id="location" name="location"></textarea>

                            
                            
                            @error('location')

                            <div class="alert alert-danger mt-2">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="col-md-12">

                            <label for="appguid" onkeyup="notspace(this);" class="form-label">App UID</label>

                            <input type="text" name="appguid" class="form-control @error('appguid') is-invalid @enderror" id="appguid" placeholder="appguid">

                            @error('appguid')

                            <div class="alert alert-danger mt-2">{{ $message }}</div>

                            @enderror

                        </div>

                        <div class="col-md-12">

                            <div class="d-md-flex d-grid align-items-center gap-3">

                                <button type="submit" class="btn btn-primary px-4">Submit</button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>







    @push('js')

        <script>

            function notspace(t) {

                if (t.value.match(/\s/g)) {


                    alert('Sorry, you are not allowed to enter any spaces');


                    t.value = t.value.replace(/\s/g, '');


                }


            }

        </script>

    @endpush

@endsection


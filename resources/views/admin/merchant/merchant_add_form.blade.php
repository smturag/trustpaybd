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
                           
                            <h5 class="mb-4">Create Merchant</h5>

                            <form class="row g-3" action="{{ route('merchantAddAction') }}" method="POST">
                                @csrf
                                <div class="col-md-12">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" id="fullname" placeholder="Full Name">
                                    @error('fullname')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" onkeyup="notspace(this);" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email">
                                    @error('email')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="mobile" onkeyup="notspace(this);" class="form-label">Mobile</label>
                                    <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" id="mobile" placeholder="Mobile">
                                    @error('mobile')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" onkeyup="notspace(this);" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
                                    @error('password')
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
function notspace(t){
if(t.value.match(/\s/g)){

alert('Sorry, you are not allowed to enter any spaces');

t.value=t.value.replace(/\s/g,'');

}

}
</script>    
@endpush
@endsection

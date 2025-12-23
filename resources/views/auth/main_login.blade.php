
{{-- @extends('welcome')
@section('customer')

	<div class="wrapper">


		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-4">

			<div class="container">

				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">

					<div class="col mx-auto">

						<div class="card my-5 my-lg-0 shadow-none border">

							<div class="card-body">

								<div class="p-4">

									<div class="text-center mb-4">

										<h5 class="">Hello! Member</h5>



										<p class="mb-0">Please log in to your account</p>



											@if($errors->any())



												<div class="alert alert-danger">

													<ul>

														@foreach ($errors->all() as $error)

															<li>{{ $error }}</li>

														@endforeach

													</ul>

												</div>





											@endif





											@if (Session::has('message'))

											<div class="alert alert-success">{{ Session::get('message') }}</div>

											@endif



											@if (Session::has('alert'))

											<div class="alert alert-danger">{{ Session::get('alert') }}</div>

											@endif

									</div>

									<div class="form-body">

										 <form action="{{ route('loginAction') }}" method="POST" class="row g-3">

											@csrf



											<div class="col-12">

												<label for="username" class="form-label">Email or Mobile</label>

												<input type="text" class="form-control" id="username" name="username"  value="{{ old('username') ? old('username') : '' }}" required>

											</div>

											<div class="col-12">

												<label for="inputChoosePassword" class="form-label">Password</label>

												<div class="input-group" id="show_hide_password">

													<input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>

												</div>

											</div>





                                             <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">





                                                    {!! app('captcha')->display() !!}

                                                    @if ($errors->has('g-recaptcha-response'))

                                                        <span class="help-block">

                                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>

                                                        </span>

                                                    @endif



											<div class="col-md-6">

												<div class="form-check form-switch">

													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">

													<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>

												</div>

											</div>



											<div class="col-12">

												<div class="d-grid">

													<button type="submit" class="btn btn-primary">Sign in</button>

												</div>

											</div>



										</form>

									</div>







								</div>

							</div>

						</div>

					</div>

				</div>

				<!--end row-->

			</div>

		</div>
	</div>

	<!--end wrapper-->
@endsection --}}


@extends('welcome')

@section('customer')
    @php
        $app_name = app_config('AppName');
        $image = app_config('AppLogo');
    @endphp

    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 pt-24">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="index.html" class="flex justify-center text-3xl font-bold text-indigo-600 mb-8">{{ $app_name }}</a>
            <h2 class="text-center text-3xl font-bold text-gray-900">Sign in to your account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">create a member account</a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-100">
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Display success/error messages from session -->
                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                @if (Session::has('alert'))
                    <div class="alert alert-danger">{{ Session::get('alert') }}</div>
                @endif

                <!-- Login Form -->
                <form class="space-y-6" action="{{ route('loginAction') }}" method="POST">
                    @csrf
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Email or Mobile</label>
                        <div class="mt-1">
                            <input id="username" name="username" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                value="{{ old('username') }}" placeholder="Enter Email or Mobile">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <div class="input-group">
                                <input id="password" name="password" type="password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter Password">
                                <span class="input-group-text bg-transparent cursor-pointer" id="togglePassword">
                                    <i class='bx bx-hide'></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="form-group">
                        {!! NoCaptcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                        </div>

                        <div class="text-sm">
                            <a href="#"
                                class="font-medium text-indigo-600 hover:text-indigo-500 flex items-center">
                                <i class="fas fa-key mr-1 text-xs"></i>
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Sign in
                        </button>
                    </div>
                </form>

                <!-- Social Login Options -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-google text-xl"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to toggle password visibility -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bx-hide');
            this.querySelector('i').classList.toggle('bx-show');
        });
    </script>
@endsection

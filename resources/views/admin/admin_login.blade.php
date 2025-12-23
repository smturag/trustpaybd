<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="{{ url('/') }}">
    <meta charset="utf-8">
    <meta name="author" content="quitworld">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Powering Data for the new equity blockchain">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="static/backend/images/favicon.png">
    <!-- Page Title  -->
    <title>{{ app_config('AppTitle') }} | Admin Login </title>
   
   	<link href="{{ asset('static/backend/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('static/backend/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('static/backend/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
	<!-- loader-->
	<link href="{{ asset('static/backend/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('static/backend/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('static/backend/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('static/backend/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('static/backend/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('static/backend/css/icons.css') }}" rel="stylesheet">
	
	<style type="text/css">
       
       input.pw {
           -webkit-text-security: circle;
        }

        .display-none {
            display: none;
        }

   </style>
    {!! NoCaptcha::renderJs() !!}
</head>



<body class="">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-cover">
			<div class="">
				<div class="row g-0">

					<div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
							<div class="card-body">
                                 <img src="{{ asset('static/backend/images/login-images/login-cover.svg') }}" class="img-fluid auth-img-cover-login" width="650" alt=""/>
							</div>
						</div>
						
					</div>

					<div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
						<div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
							<div class="card-body p-sm-5">
								<div class="">
									<div class="mb-3 text-center">
										<img src="{{ asset('static/backend/images/logo-icon.png') }}" width="60" alt="">
									</div>
									<div class="text-center mb-4">
										<h5 class=""> Admin</h5>
										<p class="mb-0">Access the {{ app_config('AppName') }} Admin panel using your username and password.</p>
									</div>
									
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
										
									<div class="form-body">
										<form class="row g-3" action="{{ route('adminloginAction') }}" method="POST">
										@csrf
											<div class="col-12">
												<label for="username" class="form-label">Username</label>
												<input type="text" name="username"  value="{{ old('username') ? old('username') : '' }}" class="form-control" id="username" placeholder="" required>
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password" required> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
												</div>
											</div>
											
											 <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">


													{!! app('captcha')->display() !!}
													@if ($errors->has('g-recaptcha-response'))
														<span class="help-block">
															<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
														</span>
													@endif

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
	

<script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
	<script src="{{ asset('static/backend/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('static/backend/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="{{ asset('static/backend/js/app.js') }}"></script>
	
	
<script>	

$("form").submit(function(){
$("button[type=submit]").attr("disabled", "disabled");
$("button[type=submit]").empty().html("Please wait...");
});
</script>
 
</html>
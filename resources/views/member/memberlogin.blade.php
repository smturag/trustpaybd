
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<link href="{{ asset('static/backend/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />

	<link href="{{ asset('static/backend/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>

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
		<header class="login-header shadow">
			<nav class="navbar navbar-expand-lg navbar-light rounded-0 bg-white fixed-top rounded-0 shadow-none border-bottom">
				<div class="container-fluid">
					<a class="navbar-brand" href="#">
						<img src="assets/images/logo-img.png" width="140" alt="" />
					</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent1">
						<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
							<li class="nav-item"> <a class="nav-link active" aria-current="page" href="#"><i class='bx bx-home-alt me-1'></i>Home</a>
							</li>
							<li class="nav-item"> <a class="nav-link" href="#"><i class='bx bx-user me-1'></i>About</a>
							</li>
							<li class="nav-item"> <a class="nav-link" href="#"><i class='bx bx-category-alt me-1'></i>Features</a>
							</li>
							<li class="nav-item"> <a class="nav-link" href="#"><i class='bx bx-microphone me-1'></i>Contact</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
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
		<footer class="bg-white shadow-none border-top p-2 text-center fixed-bottom">
			<p class="mb-0">Copyright © 2022. All right reserved.</p>
		</footer>
	</div>
	<!--end wrapper-->
	
	

	<!-- Bootstrap JS -->
	<script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>

    <!--Password show & hide js -->
	<script>

    $("form").submit(function(){
    $("button[type=submit]").attr("disabled", "disabled");
    $("button[type=submit]").empty().html("Please wait...");
    });

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
	
	
</body>

</html>
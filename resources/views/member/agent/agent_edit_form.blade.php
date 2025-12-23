@extends('member.layout.member_app')
@section('title', "Create")

@section('member_content')
	
				<div class="row">
					<div class="col-xl-6">
						
						<div class="card">
							<div class="card-body p-4">
								
								<h5 class="mb-4">{{ translate($user->user_type) }} Edit</h5>
								<form class="row g-3" action="{{ route('member_update', ['id' => $user->id]) }}" method="POST">
									@csrf
									<div class="col-md-12">
										<label for="fullname" class="form-label">Full Name</label>
										<input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" id="fullname" value="{{$user->fullname}}">
										@error('fullname')
											<div class="alert alert-danger">{{ $message }}</div>
										@enderror
									</div>
									<div class="col-md-12">
										<label for="email" class="form-label">Email</label>
										<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{$user->email}}">
										@error('email')
											<div class="alert alert-danger">{{ $message }}</div>
										@enderror
									</div>
									<div class="col-md-12">
										<label for="mobile" class="form-label">Phone</label>
										<input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" id="mobile" value="{{ $user->mobile }}">
										@error('mobile')
											<div class="alert alert-danger">{{ $message }}</div>
										@enderror
									</div>
									<div class="col-md-12">
										<label for="password" class="form-label">Password</label>
										<input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
										@error('password')
											<div class="alert alert-danger">{{ $message }}</div>
										@enderror
									</div>
									
									<div class="col-md-12">
										
										  <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="status" value="1" <?php if($user->status==1) { echo "checked='checked'";} ?>>
                                        <span class="custom-control-label">{{ translate('active')}}
                                        </span>
                                    </label>
									</div>
									<div class="col-md-12">
										<div class="d-md-flex d-grid align-items-center gap-3">
											<button type="submit" class="btn btn-primary px-4">Update</button>
										</div>
									</div>
								</form>
							</div>
							</div>
							</div>
			</div>
			

@endsection

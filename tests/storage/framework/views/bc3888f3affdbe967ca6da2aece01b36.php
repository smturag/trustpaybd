<?php $__env->startSection('customer'); ?>

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

										

											<?php if($errors->any()): ?>



												<div class="alert alert-danger">

													<ul>

														<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

															<li><?php echo e($error); ?></li>

														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													</ul>

												</div>





											<?php endif; ?>





											<?php if(Session::has('message')): ?>

											<div class="alert alert-success"><?php echo e(Session::get('message')); ?></div>

											<?php endif; ?>



											<?php if(Session::has('alert')): ?>

											<div class="alert alert-danger"><?php echo e(Session::get('alert')); ?></div>

											<?php endif; ?>

									</div>

									<div class="form-body">

										 <form action="<?php echo e(route('loginAction')); ?>" method="POST" class="row g-3">

											<?php echo csrf_field(); ?>

										

											<div class="col-12">

												<label for="username" class="form-label">Email or Mobile</label>

												<input type="text" class="form-control" id="username" name="username"  value="<?php echo e(old('username') ? old('username') : ''); ?>" required>

											</div>

											<div class="col-12">

												<label for="inputChoosePassword" class="form-label">Password</label>

												<div class="input-group" id="show_hide_password">

													<input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>

												</div>

											</div>

											



                                             <div class="form-group<?php echo e($errors->has('g-recaptcha-response') ? ' has-error' : ''); ?>">

                           

                            

                                                    <?php echo app('captcha')->display(); ?>


                                                    <?php if($errors->has('g-recaptcha-response')): ?>

                                                        <span class="help-block">

                                                            <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>

                                                        </span>

                                                    <?php endif; ?>

                                                

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/auth/main_login.blade.php ENDPATH**/ ?>
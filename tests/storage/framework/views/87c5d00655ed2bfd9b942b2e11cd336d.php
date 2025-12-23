<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startPush('css'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

    <?php if(session()->has('message')): ?>
        <div class="alert alert-success" id="alert_success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>

    <?php if(Session::has('alert')): ?>
        <div class="alert alert-danger"><?php echo e(Session::get('alert')); ?></div>
    <?php endif; ?>

    <?php
        $check_wallet = app_config('wallet_payment_status');
       $check_wallet_status = $check_wallet=="false"?0:1;
    //    dd($check_wallet_status);
    ?>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">App Config</h6>
                
            </div>
            <hr />

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div class="card">
                                <div class="card-header px-4 py-3">
                                    <h5 class="mb-0">App Config</h5>
                                </div>
                                <form action="<?php echo e(route('settings.store')); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="card-body p-4">
                                        <form class="row g-3 needs-validation was-validated" novalidate="">
                                            <div class="col-md-12">
                                                <label for="bsValidation1" class="form-label">Brand Name</label>
                                                <input type="text" class="form-control" id="bsValidation1"
                                                    placeholder="AppName" name="AppName" value="<?php echo e(app_config('AppName')); ?>"
                                                    required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="bsValidation1" class="form-label">Whatsapp Support Number</label>
                                                <input type="text" class="form-control" id="bsValidation1"
                                                    placeholder="support_whatsapp_number" name="support_whatsapp_number" value="<?php echo e(app_config('support_whatsapp_number')); ?>"
                                                    required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="bsValidation2" class="form-label">Brand Image</label>
                                                <input type="file" class="form-control" name="AppLogo" id="bsValidation2"
                                                    placeholder="logo">
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <label for="bsValidation2" class="form-label">Wallet Payment Status</label>
                                                <Select class="form-control" name="wallet_payment_status">
                                                    <option value="true"
                                                        <?php echo e($check_wallet_status=='1' ? 'selected' : ''); ?>>
                                                        Enable</option>
                                                    <option value="false"
                                                        <?php echo e($check_wallet_status=='0' ? 'selected' : ''); ?>>
                                                        Disable</option>
                                                </Select>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>

                                            </Select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                                            <button type="reset" class="btn btn-light px-4">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Transaction'); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('mrc_content'); ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session()->has('message')): ?>
        <div class="alert alert-success" id="alert_success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>

    <?php if(session()->has('alert')): ?>
        <div class="alert alert-warning" id="alert_warning">
            <?php echo e(session('alert')); ?>

        </div>
    <?php endif; ?>


    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Withdraw Request</h5>
                    <form method="post" action="<?php echo e(route('merchant.withdraw-save')); ?>" class="row g-3">
                        <?php echo csrf_field(); ?>

                        <div class="col-md-12">
                            <label for="balance"><?php echo e(translate('current_balance')); ?></label>
                            <input type="text" class="form-control" id="balance" readonly value="<?php echo e(auth('merchant')->user()->balance); ?>">
                        </div>

                        <div class="col-md-12">
                            <label for="currency_name"><?php echo e(translate('Currency Name')); ?></label>

                            <div class="logo-wrap">
                                <select name="currency_name" class="form-control" id="currency_name">
                                    <option value=""><?php echo e(translate('select')); ?></option>

                                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($method->currency_name); ?>"><?php echo e($method->currency_name); ?></option>
                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="network"><?php echo e(translate('Network')); ?></label>

                            <div class="logo-wrap">
                                <select name="network" class="form-control" id="network">
                                    <option value=""><?php echo e(translate('select')); ?></option>

                                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($method->network); ?>"><?php echo e($method->network); ?></option>
                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="deposit_address" class="form-label">Deposit Address</label>
                            <input type="text" class="form-control" id="deposit_address" name="deposit_address" value="<?php echo e(old('deposit_address')); ?>">
                        </div>

                        <div class="col-md-12">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="text" onkeyup="notspace(this);" class="form-control" id="amount" name="amount" value="<?php echo e(old('amount')); ?>">
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('merchant.mrc_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/withdraw.blade.php ENDPATH**/ ?>
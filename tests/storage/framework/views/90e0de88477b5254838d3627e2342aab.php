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


    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">MFS Create</h5>
                    <hr>
                    <form action="<?php echo e(route('mfs.insert_mfs')); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
                        <?php echo csrf_field(); ?>
                        <div class="col-md-12">
                            <label for="input3" class="form-label">MFS Name</label>
                            <input type="text" name="mfs_name" class="form-control" id="input3"
                                placeholder="MFS Name">
                            <?php $__errorArgs = ['mfs_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-12">

                            <label for="input3" class="form-label">MFS Status</label>
                            <div class="form-check form-switch">

                                <input class="form-check-input"  id="switch-button" type="checkbox" role="switch"
                                    id="flexSwitchCheckDefault1" name="mfs_status" checked="">
                                <label class="form-check-label" id="status-label" for="flexSwitchCheckDefault1">off</label>
                                
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <input type="file" name="mfs_logo" class="form-control" id="inputGroupFile02">
                                <label class="input-group-text" for="inputGroupFile02">Upload MFS Logo</label>
                                <?php $__errorArgs = ['mfs_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="error"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        $(document).ready(function() {
            // Initially set the label to "Off"
            $('#status-label').text('on');
            //   $('#switch-button').val(1);

            // Add event listener to the switch button
            $('#switch-button').on('change', function() {
                if ($(this).is(':checked')) {
                    // Switch is on
                    $('#status-label').text('on');
                    //   $('#switch-button').val(1);
                } else {
                    // Switch is off
                    $('#status-label').text('off');
                    //   $('#switch-button').val(0);
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/mfs_operator/create_mfs.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('mrc_content'); ?>

    <?php $__env->startPush('css'); ?>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    <?php $__env->stopPush(); ?>
    <?php if(session()->has('message')): ?>
        <div class="alert alert-success" id="alert_success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>

    <?php if(Session::has('alert')): ?>
        <div class="alert alert-danger"><?php echo e(Session::get('alert')); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Sub Merchant List</h6>
                
                <a class="ms-auto btn btn-sm btn-primary" href="<?php echo e(route('sub_merchant.create')); ?>">
                    <i class="bx bx-plus mr-1"></i> Sub Merchant Create
                </a>
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
                    <div class="item-content">
                        <div class="mb-3">
                            <table class="table table-striped table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Merchant ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('merchant.mrc_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/sub_merchant/index.blade.php ENDPATH**/ ?>
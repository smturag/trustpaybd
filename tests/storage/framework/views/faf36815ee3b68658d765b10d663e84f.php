<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<?php $__env->stopPush(); ?>



<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-none d-sm-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Service Request List</h6>
            </div>
            <hr />

            <?php if(session('message')): ?>
                <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-white">Success Alerts</h6>
                            <div class="text-white"><?php echo e(session('message')); ?></div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-hover text-center" id="myTable">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Merchant</th>
                                <th>Service</th>
                                <th>Rate</th>
                                <th>Charge(%)</th>
                                <th>Commission(%)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($item->merchant->fullname); ?></td>
                                    <td><?php echo e($item->service->name); ?></td>
                                    <td><?php echo e($item->rate); ?></td>
                                    <td><?php echo e($item->charge); ?></td>
                                    <td><?php echo e($item->commission); ?></td>
                                    <td>
                                        <a onclick="return confirm('are you sure want change status!!')" href="<?php echo e(route('merchant.service.status', ['id' => $item->id])); ?>"> <span
                                                class="badge <?php echo e($item->status == 1 ? 'bg-success' : 'bg-danger'); ?>"><?php echo e($item->status == 1 ? 'Active' : 'Deactive'); ?></span></a>

                                    </td>
                                    <td>Action</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('js'); ?>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/merchant_service/index.blade.php ENDPATH**/ ?>
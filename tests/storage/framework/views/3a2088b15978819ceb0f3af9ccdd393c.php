<?php $__env->startSection('title', 'Service'); ?>
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
                    <div class="table-responsive">
                        <table class="tabletable-striped table-hover text-center" id="myTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td><?php echo e($item->name); ?></td>
                                        <td>
                                            <?php if($item->status == 0): ?>
                                                <a href="<?php echo e(route('service.change_status', ['id' => $item->id])); ?>" onclick="return confirm('Are you sure you want to change the status?')">
                                                    <span class="badge bg-danger">Deactivate</span>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('service.change_status', ['id' => $item->id])); ?>" onclick="return confirm('Are you sure you want to change the status?')">
                                                    <span class="badge bg-success">Active</span>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
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

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/service/index.blade.php ENDPATH**/ ?>
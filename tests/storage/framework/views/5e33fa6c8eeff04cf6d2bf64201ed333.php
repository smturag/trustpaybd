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
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0 text-uppercase">Withdraw List</h6>
                <hr/>
                <div class="table-responsive">
                    <table id="trade_list" class="table table-striped table-bordered mb-0">
                        <thead>
                        <tr>
                            <th><?php echo e(translate('sl')); ?></th>
                            <th><?php echo e(translate('type')); ?></th>
                            <th><?php echo e(translate('Currency Name')); ?></th>
                            <th><?php echo e(translate('Network')); ?></th>
                            <th><?php echo e(translate('Deposit Address')); ?></th>
                            <th><?php echo e(translate('trxid')); ?></th>
                            <th><?php echo e(translate('debit')); ?></th>
                            <th><?php echo e(translate('date')); ?></th>
                            <th><?php echo e(translate('status')); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $all_request; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            if ($drow->status == 0) {
                                $stsmsg = "<font color='black'>Pending</font>";
                            } elseif ( $drow->status == 1) {
                                $stsmsg = "<font color='green'>Success</font>";
                            }  elseif ($drow->status == 2) {
                                $stsmsg = "<font color='red'>Reject</font>";
                            }

                            // <?php
                            // if ($drow->status == 0) {
                            //     $stsmsg = "<font color='black'>Pending</font>";
                            // } elseif ($drow->status == 2) {
                            //     $stsmsg = "<font color='green'>Success</font>";
                            // } elseif ($drow->status == 3) {
                            //     $stsmsg = "<font color='green'>Approved</font>";
                            // } elseif ($drow->status == 1) {
                            //     $stsmsg = "<font color='blue'>Waiting</font>";
                            // } elseif ($drow->status == 4) {
                            //     $stsmsg = "<font color='red'>Fail</font>";
                            // }


                            ?>

                            <tr>
                                <td class="text-truncate"><?php echo e($loop->iteration); ?></td>
                                <td class="text-truncate"><?php echo e($drow->type); ?></td>
                                <td class="text-truncate"><?php echo e($drow->currency_name); ?></td>
                                <td class="text-truncate"><?php echo e($drow->network); ?></td>
                                <td class="text-truncate"><?php echo e($drow->deposit_address); ?></td>
                                <td class="text-truncate"><?php echo e($drow->trxid); ?></td>
                                <td class="text-truncate"><?php echo e($drow->debit); ?></td>
                                <td class="text-truncate"><?php echo e($drow->created_at); ?></td>
                                <td class="text-truncate"><?php echo $stsmsg; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('merchant.mrc_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/withdraw-list.blade.php ENDPATH**/ ?>
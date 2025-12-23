<?php ($url_number = Request::segment(3)); ?>

<table id="transaction_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col" class="text-center">Sender</th>
        <th scope="col">Type</th>
        <th scope="col">C Number</th>
        <th scope="col">Oldbal</th>
        <th scope="col">Amount</th>
        <th scope="col">Comm.</th>
        <th scope="col">Lastbal</th>
        <th scope="col">Trxid</th>
        <th scope="col">A Number</th>
        <th scope="col">Status</th>
        <th scope="col">Date</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr id="<?php echo e($row->id); ?>">
            <td><?php echo e($row->id); ?></td>
            <td><?php echo e($row->sender); ?></td>
            <td style="text-transform: capitalize;"><?php echo e(str_replace("ng", "", $row->type)); ?></td>
            <td><?php echo e($row->mobile); ?></td>
            <td><?php echo e(money($row->oldbal)); ?></td>
            <td><?php echo e(money($row->amount)); ?></td>
            <td><?php echo e(money($row->commission)); ?></td>
            <td><?php echo e(money($row->lastbal)); ?></td>
            <td><?php echo e($row->trxid); ?></td>
            <td><?php echo e($row->sim); ?></td>
            <?php if($row->status == 20 || $row->status == 22): ?>
                <td><span class='badge badge-pill bg-success'>Success</span></td>
            <?php elseif($row->status == 33): ?>
                <td><span class='badge badge-pill bg-info text-white'>Waiting</span></td>
            <?php elseif($row->status == 55): ?>
                <td><span class='badge badge-pill bg-danger text-white'>Danger</span></td>
            <?php elseif($row->status == 66): ?>
                <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
            <?php elseif($row->status == 77): ?>
                <td><span class='badge badge-pill bg-success text-white'>Approved</span></td>
            <?php else: ?>
                <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>
            <?php endif; ?>
            <td>
                <?php echo e($row->sms_time); ?>

                <p class="text-success font-weight-bold">
                    <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->sms_time))->diffForHumans() ?>
                </p>
            </td>
            <td>
				<?php if(auth()->user('web')->user_type=='agent'): ?>
			
                <?php if($row->status == 20 || $row->status == 22 || $row->status == 77 || $row->status == 66): ?> <?php else: ?>
				
                    <a href="#" class="openPopup btn btn-sm btn-success" id="<?php echo e($row->id); ?>" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-check-double" aria-hidden="true"></i></a>
                    <!--<a href="#" class="openPopup btn btn-sm btn-success" data-href="<?php echo e(route('approved_transaction', $row->id)); ?>" id="<?php echo e($row->id); ?>" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-check-double" aria-hidden="true"></i></a>-->
                    <a href="#" class="rejectBalance btn btn-sm btn-outline-danger" id="<?php echo e($row->id); ?>"><i class="lni lni-cross-circle" aria-hidden="true"></i></a>
                <?php endif; ?>
				

                <a href="#" class="openPopup btn btn-sm btn-outline-info" data-bs-toggle="modal" data-href="<?php echo e(route('view_transaction', $row->id)); ?>" data-bs-target="#myModal"><i class="lni lni-eye" aria-hidden="true"></i></a>
				<?php else: ?>
				<?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if($data->isEmpty()): ?>
        <tr>
            <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/transaction-content.blade.php ENDPATH**/ ?>
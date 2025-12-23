<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col" class="text-center">Member Code</th>
        <th scope="col">Full Name</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Type</th>
        <th scope="col">Balance</th>
        <th scope="col" class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<?php
		$total += $row->balance;
	?>
        <tr id="<?php echo e($row->id); ?>">
            <td class="text-center"><?php echo e($row->id); ?></td>
            <td class="text-center"><?php echo e($row->member_code); ?></td>
            <td><?php echo e($row->fullname); ?></td>
            <td><?php echo e($row->email); ?></td>
            <td><?php echo e($row->mobile); ?></td>
            <td><?php echo e($row->user_type); ?></td>
			 <td><?php echo e(findAgentBalance($row->id)['mainBalance']); ?></td>
            <td class="text-center">
                <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('member_edit', ['id' => $row->id])); ?>">Edit</a>
                <button class="btn btn-sm btn-outline-danger delete" id="<?php echo e($row->id); ?>" onClick="delete_record(this.id, '<?php echo e($row->fullname); ?>')">Delete</button>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




	<?php if($data->isEmpty()): ?>
        <tr>
            <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
        </tr>
	<?php else: ?>

		<tr>
		<th colspan="6">Total</th>
		<th><?php echo number_format($total,2)?></th>
		<th colspan="6">&nbsp;</th>
		</tr>

    <?php endif; ?>
    </tbody>
</table>

<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/agent/agent_data.blade.php ENDPATH**/ ?>
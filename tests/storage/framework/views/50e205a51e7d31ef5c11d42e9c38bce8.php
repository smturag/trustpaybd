<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
		<th scope="col">Date</th>
        <th scope="col">Agent</th>
        <th scope="col">Deviceid</th>
        <th scope="col">Operator</th>
        <th scope="col">Sim</th>

        <th scope="col">Action</th>

    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="text-center"><?php echo e($row->id); ?></td>
			<td>
                <?php echo e(date('d F Y', strtotime($row->created_at))); ?><br>
                <?php echo e(date('h:i:s A', strtotime($row->created_at))); ?>

            </td>
            <td ><?php echo e($row->member_code); ?></td>
            <td><?php echo e($row->deviceid); ?></td>
            <td><?php echo e($row->operator); ?></td>
            <td><?php echo e($row->sim_number); ?></td>

			 <td class="text-center">

                <button class="btn btn-sm btn-outline-danger delete" id="<?php echo e($row->id); ?>" onClick="delete_record(this.id, '<?php echo e($row->deviceid); ?>')">Delete</button>
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

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/member_modem/modem_table_content.blade.php ENDPATH**/ ?>
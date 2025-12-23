<table class="table table-hover table-bordered mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
		<th scope="col">date</th>
        <th scope="col" class="text-center">Sender</th>
		 <th scope="col">Sim</th>
        <th scope="col">Message</th>
        <th scope="col">Operator</th>
        <th scope="col">Sim Slot</th>
        
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="text-center"><?php echo e($row->id); ?></td>
			<td><?php echo e(bdtime($row->created_at)); ?></td>
            <td class="text-center"><?php echo e($row->sender); ?></td>
			<td><?php echo e($row->sim_number); ?></td>
            <td id="multiline-text"><?php echo e($row->sms); ?></td>
            <td><?php echo e($row->operator); ?></td>
            <td><?php echo e($row->simslot); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if($data->isEmpty()): ?>
        <tr>
            <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<style>
    #multiline-text {
  width: 200px; /* Adjust the width as needed */
  height: auto;
  border: 1px solid #000;
  padding: 10px;
  white-space: pre-wrap;
}
</style>

<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/member_sms/inbox_table_content.blade.php ENDPATH**/ ?>
<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">Merchant ID</th>
            <th scope="col">Full Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>

            <th scope="col">Balance</th>
            <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>


        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            // 		$total += $row->balance;
            
            $balance = getMerchantBalance($row->id);
            
            ?>
            <tr id="<?php echo e($row->id); ?>">
                <td class="text-center"><?php echo e($row->id); ?></td>
                <td class="text-center"><?php echo e($row->username); ?></td>
                <td><?php echo e($row->fullname); ?></td>
                <td><?php echo e($row->email); ?></td>
                <td><?php echo e($row->mobile); ?></td>

                <td>

                    <a href="javascript:void(0)" data-id="<?php echo e($row->id); ?>" data-name="<?php echo e($row->username); ?>"
                        data-balance="<?php echo e($balance['balance']); ?>"
                        data-route="<?php echo e(route('merchant_add_balance', $row->id)); ?>" data-bs-toggle="modal"
                        data-bs-target="#editModal" class="btn btn-sm btn-primary text-white editBtn btn-sm me-2"><i
                            class="bx bx-money"></i><?php echo e($balance['balance']); ?></a>


                </td>
                <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary"
                        href="<?php echo e(route('merchant_edit', ['id' => $row->id])); ?>">Edit</a>
                    <button class="btn btn-sm btn-outline-danger delete" id="<?php echo e($row->id); ?>"
                        onClick="delete_record(this.id, '<?php echo e($row->fullname); ?>')">Delete</button>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




        <?php if($data->isEmpty()): ?>
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        <?php else: ?>
            <tr>
                <th colspan="5">Total</th>
                <th><?php echo number_format($total, 2); ?></th>
                <th colspan="6">&nbsp;</th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/merchant/admin_merchant_data.blade.php ENDPATH**/ ?>
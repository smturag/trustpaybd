<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">Member Code</th>
            <th scope="col">Full Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Type</th>
            <th scope="col">Auto Req Activated</th>
            <th scope="col">Parent Code</th>
            <th scope="col">Balance</th>
            <th scope="col" class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $total += $row->balance; ?>
            <tr id="<?php echo e($row->id); ?>">
                <td class="text-center"><?php echo e($row->id); ?></td>
                <td class="text-center"><?php echo e($row->member_code); ?></td>
                <td><?php echo e($row->fullname); ?></td>
                <td><?php echo e($row->email); ?></td>
                <td class="text-center"><?php echo e($row->mobile); ?></td>
                <td class="text-center text-capitalize"><?php echo e($row->user_type); ?></td>
                <td class="text-center text-capitalize">
                    <?php if($row->user_type == 'agent'): ?>
                        <a href="<?php echo e(route('agent_active', ['agent_id' => $row->id])); ?>"
                            class="<?php echo e($row->auto_active_agent == 0 ? 'btn btn-primary' : 'btn btn-danger'); ?>">
                            <?php if($row->auto_active_agent == 0): ?>
                                Deactive
                            <?php else: ?>
                                Active
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                </td>
                <td class="text-center">
                    <?php $member_code = \DB::table('users')
                        ->where('create_by', $row->id)
                        ->value('member_code'); ?>
                    <?php if($member_code): ?>
                        <button class="btn btn-sm btn-outline-info" id="<?php echo e($row->id); ?>"
                            onClick="view_parent_detail(this.id, '<?php echo e($row->fullname); ?>')"><?php echo e($member_code); ?></button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>

                <?php
                    if ($row->user_type == 'agent') {
                        $getBalance = findAgentBalance($row->id);
                    }else if($row->user_type == 'partner'){
                        $getBalance = partnerBalance($row->id);
                    }

                ?>
                <td class="text-center">
                    

                    <a href="javascript:void(0)" data-id="<?php echo e($row->id); ?>" data-name="<?php echo e($row->fullname); ?>"
                        data-balance="<?php echo e($getBalance['mainBalance']); ?>"
                        data-route="<?php echo e(route('agent_add_balance', $row->id)); ?>" data-bs-toggle="modal"
                        data-bs-target="#editModal" class="btn btn-sm btn-primary text-white editBtn btn-sm me-2"><i
                            class="bx bx-money"></i><?php echo e($getBalance['mainBalance']); ?></a>


                </td>
                <td class="text-center">
                    <a class="btn btn-sm btn-outline-primary"
                        href="<?php echo e(route('user_edit', ['id' => $row->id])); ?>">Edit</a>
                    
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        <?php if($data->isEmpty()): ?>
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        <?php else: ?>
            <tr>
                <th colspan="7">Total</th>
                <th><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/users/user_data.blade.php ENDPATH**/ ?>
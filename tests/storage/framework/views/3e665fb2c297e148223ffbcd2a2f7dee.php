<table id="req_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
        <tr>
            <th scope="col" class="text-center">
                <input type="checkbox" id="select_all" />
            </th>
            
            <th scope="col">ReqTime <br> Date</th>
            <th scope="col">MFS</th>
            <th scope="col">Number</th>
            <th scope="col">Oldbal</th>
            <th scope="col">Amount</th>
            <th scope="col">Lastbal</th>
            <th scope="col">Trxid</th>
            <th scope="col">Type</th>
            <th scope="col">Status</th>
            <th scope="col">Route</th>
            <?php if($user_type == 'agent'): ?>
                <th scope="col">Action</th>
            <?php endif; ?>
        </tr>
    </thead>

    <?php
        $total = 0;
    ?>

    <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $total += $row->amount;
            ?>

            <tr id="<?php echo e($row->id); ?>">
                <td>
                    <?php if($row->status == 5 || $row->status == 6): ?>
                    <input type="checkbox" class="row_checkbox" />
                    <?php endif; ?>
                </td>
                
                <td><?php echo e(bdtime($row->created_at)); ?>

                    <p class="text-success font-weight-bold">
                        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(); ?>
                    </p> <br>
                    <?php echo e($row->updated_at); ?>

                    <p class="text-success font-weight-bold">
                        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans(); ?>
                    </p>
                </td>
                <td style="text-transform: capitalize;"><?php echo e($row->mfs); ?></td>
                <td><?php echo e($row->number); ?></td>
                <td><?php echo e(money($row->old_balance)); ?></td>
                <td><?php echo e(money($row->amount)); ?></td>
                <td><?php echo e(money($row->new_balance)); ?></td>
                <td style="width: 150px; word-wrap: break-word; white-space: normal;">
                    <?php echo e($row->get_trxid); ?>

                </td>
                <td><?php echo e($row->type); ?></td>
                
                <?php if($row->status == 2): ?>
                    <td><span class='badge badge-pill bg-success'>Success</span></td>
                <?php elseif($row->status == 1): ?>
                    <td><span class='badge badge-pill bg-info text-white'>Waiting</span></td>
                <?php elseif($row->status == 4): ?>
                    <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
                <?php elseif($row->status == 3): ?>
                    <td><span class='badge badge-pill bg-success text-white'>Approved</span></td>
                <?php elseif($row->status == 5): ?>
                    <td><span class='badge badge-pill text-white' style="background-color: blue">Processing</span></td>
                <?php elseif($row->status == 6): ?>
                    <td><span class='badge badge-pill bg-green text-white'
                            style="background-color: chocolate">Failed</span></td>
                <?php else: ?>
                    <td><span class='badge badge-pill bg-warning text-white'>Pending</span></td>
                <?php endif; ?>
                <td>
                    <?php if($row->agent_id): ?>
                        <br><span class='badge badge-pill'
                            style="color: #e6be96; background-color: blue"><?php echo e($row->user->fullname); ?></span>
                        <br><span class='badge badge-pill'
                            style="color: #e6be96; background-color: blue"><?php echo e(getSimInfo($row->modem_id)); ?></span>
                    <?php endif; ?>
                </td>

                <?php if($user_type == 'agent'): ?>
                    <?php if($row->status == 0): ?>
                        <td>
                            <?php
                                $user = Auth::guard('web')->user();
                                $getBalance = findAgentBalance($user->id);
                            ?>

                            <?php if($getBalance['mainBalance'] >= $row->amount): ?>
                                <a href="#" class="acceptRequest btn btn-sm btn-success"
                                    data-title="<?php echo e($row->mfs); ?> -- <?php echo e($row->number); ?>"
                                    id="<?php echo e($row->id); ?>">Accept</a>
                            <?php else: ?>
                                <a href="#" class="warningRequest btn btn-sm btn-success"
                                    onclick="return confirm('Your balance is insufficent')">Accept</a>
                            <?php endif; ?>
                        </td>
                    <?php else: ?>
                        <td>
                            <?php if($row->status == 2 || $row->status == 3 || $row->status == 4): ?>
                            <?php else: ?>
                                <a href="#" class="btn btn-sm btn-success showRequestConfirmModal"
                                    data-title="<?php echo e($row->mfs); ?> -- <?php echo e($row->number); ?>"
                                    id="<?php echo e($row->id); ?>"><i class="bx bx-check-double" aria-hidden="true"></i></a>


                                <a  class="rejectBalance btn btn-sm btn-outline-danger"
                                    id="<?php echo e($row->id); ?>" data-id=<?php echo e($row->id); ?>><i
                                        class="lni lni-cross-circle" aria-hidden="true"></i></a>
                            <?php endif; ?>

                            <?php if($row->status == 5 || $row->status == 6): ?>
                                <a class="btn btn-sm btn-outline-info"
                                    onclick="return confirm('Are you sure to resend this request?')"
                                    href="<?php echo e(route('agent.resend_req', ['id' => $row->id])); ?>" data-toggle="tooltip"
                                    data-placement="right" title="Resend"><i class="lni lni-reload"></i></a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($data->isEmpty()): ?>
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        <?php else: ?>
            <tr>
                <th colspan="5">Total</th>
                <th class="text-center"><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/mfs_request/mfs_table_content.blade.php ENDPATH**/ ?>
<table id="req_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
        <tr>
            <th scope="col" class="text-center">
                <input type="checkbox" id="select_all" />
            </th>
            <th scope="col" class="text-center">#</th>
            
            <th scope="col">ReqTime</th>
            <th scope="col">Name</th>
            <th scope="col">MFS</th>
            <th scope="col">Number</th>
            <th scope="col">Oldbal</th>
            <th scope="col">Amount</th>
            <th scope="col">Lastbal</th>
            <th scope="col">Trxid</th>
            <th scope="col">Type</th>
            <th scope="col">Status</th>
            <th scope="col">Route</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0 ?>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
             $total += $row->amount ;
        ?>
            <tr id="<?php echo e($row->id); ?>">
                <td>
                    <?php if($row->status == 5 || $row->status == 6): ?>
                    <input type="checkbox" class="row_checkbox" />
                    <?php endif; ?>
                </td>
                <td><?php echo e($row->id); ?></td>
                <td><?php echo e(bdtime($row->created_at)); ?>

                    <p class="text-success font-weight-bold">
                        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(); ?>
                    </p>

                    <?php echo e(bdtime($row->updated_at)); ?>

                    <p class="text-success font-weight-bold">
                        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans(); ?>
                    </p>
                </td>
                <td><?php echo e($row->merchant->fullname); ?></td>

                <?php if($row->customer_id): ?>
                    <?php

                        $get_receiver_customer = App\Models\Customer::find($row->customer_id);

                    ?>
                    <td><?php echo e($get_receiver_customer->customer_name); ?></td>
                <?php endif; ?>
                
                <td style="text-transform: capitalize;"><?php echo e($row->mfs); ?></td>
                <td><?php echo e($row->number); ?></td>
                <td><?php echo e(money($row->old_balance)); ?></td>
                <td><?php echo e(money($row->amount)); ?></td>

                <td><?php echo e(money($row->new_balance)); ?></td>
                <td style="width: 150px; word-wrap: break-word; white-space: normal;">
                    <?php echo e($row->get_trxid); ?>

                </td>

                <?php
                    $make_type = '';
                    if ($row->type == 'agent') {
                        $make_type = 'Cash Out';
                    } elseif ($row->type == 'personal') {
                        $make_type = 'Cash In';
                    } elseif ($row->type == 'merchant') {
                        $make_type = 'Payment';
                    }
                ?>

                <td><?php echo e($make_type); ?></td>


                <td>
                    <?php if($row->status == 2): ?>
                        <span class='badge badge-pill bg-success'>Success</span>
                    <?php elseif($row->status == 1): ?>
                        <span class='badge badge-pill bg-info text-white'>Waiting</span>
                    <?php elseif($row->status == 4): ?>
                        <span class='badge badge-pill bg-danger text-white'>Rejected</span>
                    <?php elseif($row->status == 3): ?>
                        <span class='badge badge-pill bg-success text-white'>Approved</span>
                    <?php elseif($row->status == 5): ?>
                        <span class='badge badge-pill  text-white' style="background-color: blue">Processing</span>
                    <?php elseif($row->status == 6): ?>
                        <span class='badge badge-pill bg-green text-white'
                            style="background-color: chocolate">Failed</span>
                    <?php else: ?>
                        <span class='badge badge-pill bg-warning text-white'> Pending </span>
                    <?php endif; ?>


                </td>

                <td>
                    <?php if($row->agent_id): ?>
                        <br><span class='badge badge-pill'
                            style="color: #e6be96; background-color: blue"><?php echo e($row->user->fullname); ?></span>
                        <br><span class='badge badge-pill'
                            style="color: #e6be96; background-color: blue"><?php echo e(getSimInfo($row->modem_id)); ?></span>
                    <?php endif; ?>

                </td>

                <td>

                    <?php
                        $make_user = '';
                        $make_type = '';
                        if ($row->merchant_id) {
                            $make_user = $row->merchant_id;
                            $make_type = 'merchant';
                        } else {
                            $make_user = $row->customer_id;
                            $make_type = 'customer';
                        }

                    ?>

                    <?php if($row->status == 2 || $row->status == 3 || $row->status == 4): ?>
                    <?php else: ?>
                        <a href="#" class="openPopup btn btn-sm btn-success" id="<?php echo e($row->id); ?>"
                            data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-check-double"
                                aria-hidden="true"></i></a>
                        
                        

                        

                        <a class="btn btn-sm btn-outline-danger" data-user_type="<?php echo e($make_type); ?>"
                            data-user="<?php echo e($make_user); ?>" data-id="<?php echo e($row->id); ?>">
                            <i class="reject_trans lni lni-cross-circle" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>

                    <?php if($row->status == 5 || $row->status == 6): ?>
                        <a class="btn btn-sm btn-outline-info" href="<?php echo e(route('resend_req', ['id' => $row->id])); ?>"
                            onclick="return confirm('Are you sure to resend this request?')" data-toggle="tooltip"
                            data-placement="right" title="Resend"><i class="lni lni-reload"></i></a>
                    <?php endif; ?>




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

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/mfs/mfs_table_content.blade.php ENDPATH**/ ?>
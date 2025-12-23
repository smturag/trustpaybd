<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            
            <th scope="col">Number</th>
            <th scope="col">Method</th>
            
            <th scope="col">Old Balance</th>
            <th scope="col">Amount</th>
            <th scope="col">New Balance</th>
            <!--<th scope="col" class="text-center">TrxId </th>-->
            <th scope="col">TrxId</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            
        </tr>
    </thead>
    <tbody>

        <?php $total = 0 ?>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            

            <?php
                $total += $row->amount;
            ?>


            <tr id="<?php echo e($row->id); ?>">
                <td class="text-center"><?php echo e(++$key); ?></td>
                <td class="text-center"><?php echo e($row->number); ?></td>
                <td class="text-center"><?php echo e($row->mfs); ?></td>


                <!--<td class="text-center"><?php echo e(fake()->randomElement(['cashin', 'cashout', 'send money'])); ?></td>-->
                

                <td class="text-center"><?php echo e($row->old_balance); ?></td>
                <td class="text-center"><?php echo e($row->amount); ?></td>
                <td class="text-center"><?php echo e($row->new_balance); ?></td>
                <!--<td>-->
                <!--    <span class="text-success"><?php echo e($row->trxid); ?> </span>-->

                <!--</td>-->
                <td style="width: 150px; word-wrap: break-word; white-space: normal;">
                    <?php echo e($row->get_trxid); ?>

                </td>

                <td class="text-center">
                    <?php echo e($row->created_at->format('h:i:sa, d-m-Y')); ?>,
                    <br>
                    <span class="text-success font-weight-bold"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(); ?></span>
                    <br>
                    <?php echo e($row->updated_at->format('h:i:sa, d-m-Y')); ?>,
                    <br>
                    <span class="text-success font-weight-bold"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans(); ?></span>
                </td>
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
                        

                            <span class='badge badge-pill bg-warning text-white'> Pending </span>
                    <?php else: ?>
                        <span class='badge badge-pill bg-warning text-white'> Pending </span>
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
                <th colspan="4">Total</th>
                <th><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/service-request/data.blade.php ENDPATH**/ ?>
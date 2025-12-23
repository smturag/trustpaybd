<style>
    .note-container {
        width: 100%;
        /* Set to the width of the table cell */
        overflow: hidden;
        /* Hide overflow content */
        position: relative;
        /* Keep the position relative if needed for other styles */
    }

    .note-content {
        display: inline-block;
        white-space: normal;
        /* Allow text to wrap onto the next line */
        word-wrap: break-word;
        /* Break long words onto the next line */
        word-break: break-all;
        /* Ensure long unbroken strings wrap to the next line */
    }
</style>

<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            
            <th scope="col">Method</th>
            <th scope="col">Type</th>
            <th scope="col">Amount</th>
            <th scope="col" class="text-center">TrxId - Reference</th>
            <!--<th scope="col">Reference Id</th>-->
            <th scope="col">From </th>

            <th scope="col">Note</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <?php if($user_type == 'agent'): ?>
                <th scope="col" class="text-center">Action</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>

        <?php $total = 0 ?>


        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $total += $row->amount;
                $make_method = '';
                $get_method = \App\Models\payment_method::where('sim_id', $row->sim_id)->first();

                if ($get_method->type == 'agent') {
                    $make_method = 'Cashout';
                } elseif ($get_method->type == 'personal') {
                    $make_method = 'Send Money';
                } elseif ($get_method->type == ' customer') {
                    $make_method = 'Payment';
                }

                if ($row->customer_id) {
                    $customer = App\Models\Customer::find($row->customer_id);
                } elseif ($row->merchant_id) {
                    $Merchant = App\Models\Merchant::find($row->merchant_id);
                }

                $BMData = DB::table('balance_managers')
                    ->where('trxid', $row->payment_method_trx)
                    ->first();

            ?>
            <tr id="<?php echo e($row->id); ?>">
                <td class="text-center"><?php echo e(++$key); ?></td>
                
                
                

                <td class="text-center"><?php echo e($row->payment_method); ?>, <?php echo e($row->sim_id); ?></td>
                <td class="text-center"><?php echo e($make_method); ?></td>
                <td class="text-center"><?php echo e($row->amount); ?></td>
                <td><?php echo e($row->payment_method_trx); ?> <span class="text-success"> <br> <?php echo e($row->reference); ?> </span></td>
                <!--<td><?php echo e($row->reference); ?></td>-->
                <td class="text-center"> <?php echo e($BMData ? $BMData->mobile : ''); ?> <span
                        class="text-success font-weight-bold">
                        <br> <?php echo e($row->cust_name); ?> <br> <?php echo e($row->cust_phone); ?> </span></td>
                <td>
                    <div class="note-container">
                        <div class="note-content">
                            <?php echo e($row->reject_msg); ?>

                        </div>
                    </div>
                </td>

                <td class="text-center">
                    <?php if($row->created_at): ?>
                        <?php echo e($row->created_at->format('h:i:sa, d-m-Y')); ?>,
                        <br>
                        <span
                            class="text-success font-weight-bold"><?php echo e(\Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans()); ?></span>
                    <?php endif; ?>
                    <br>
                    <?php if($row->updated_at): ?>
                        <?php echo e($row->updated_at->format('h:i:sa, d-m-Y')); ?>,
                        <br>
                        <span
                            class="text-success font-weight-bold"><?php echo e(\Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans()); ?></span>
                    <?php endif; ?>
                </td>
                <?php if($row->status == 0): ?>
                    <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>
                <?php elseif($row->status == 1): ?>
                    <td><span class='badge badge-pill bg-success text-white'>
                            <i
                                class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Success</span>
                    </td>
                <?php elseif($row->status == 2): ?>
                    <td><span class='badge badge-pill bg-success text-white'>
                            <i
                                class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Approved</span>
                    </td>
                <?php elseif($row->status == 3): ?>
                    <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
                <?php else: ?>
                    <td></td>
                <?php endif; ?>

                <?php if($user_type == 'agent'): ?>
                    <td>

                        <?php if($row->status == 0): ?>
                            <a href="#" data-payment-id="<?php echo e($row->id); ?>"
                                class=" declineButton openPopup btn btn-sm btn-outline-danger"><i
                                    class="lni lni-cross-circle" aria-hidden="true"></i></a>


                            <a href="#" data-payment-id="<?php echo e($row->id); ?>"
                                class=" completePaymentBtn openPopup btn btn-sm btn-success"><i
                                    class="bx bx-check-double" aria-hidden="true"></i></a>
                        <?php endif; ?>

                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        <?php if($data->isEmpty()): ?>
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        <?php else: ?>
            <tr>
                <th colspan="3">Total</th>
                <th class="text-center"><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo $data->links('common.pagination-custom'); ?>


<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/payment_request/data.blade.php ENDPATH**/ ?>
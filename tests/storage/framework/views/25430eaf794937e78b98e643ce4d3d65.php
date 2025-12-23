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
            <th scope="col">Name</th>

            <th scope="col">Method</th>
            <th scope="col">MFS Method/Trx</th>
            <th scope="col">Type</th>
            <th scope="col">Amount</th>
            
            <!--<th scope="col">Reference</th>-->
            <th scope="col">From </th>

            <th scope="col">Note</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>


        <?php $total = 0 ?>

        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $total += $row->amount;
                $make_method = '';
                $get_method = \App\Models\payment_method::where('sim_id', $row->sim_id)->first();
                $make_method = 'Payment';
                if ($get_method->type == 'agent') {
                    $make_method = 'Cashout';
                } elseif ($get_method->type == 'personal') {
                    $make_method = 'Send Money';
                } elseif ($get_method->type == ' customer') {
                    $make_method = 'Payment';
                }

                if ($row->customer_id) {
                    $customer = CustomerInfo($row->customer_id);
                } elseif ($row->merchant_id) {
                    $Merchant = App\Models\Merchant::find($row->merchant_id);
                }

                $BMData = DB::table('balance_managers')
                    ->where('trxid', $row->payment_method_trx)
                    ->first();

            ?>
            <tr id="<?php echo e($row->id); ?>">
                <td class="text-center"><?php echo e(++$key); ?></td>


                <?php if($row->customer_id): ?>
                    <td class="text-center"><?php echo e($customer->customer_name); ?> <br> <span
                            class="text-success font-weight-bold"></span> </td>
                <?php else: ?>
                    <td class="text-center"><?php echo e($Merchant->fullname); ?> <br> <span class="text-success font-weight-bold">
                            Merchant </span> <br> <span class="text-info"><?php echo e($row->reference); ?></td>
                <?php endif; ?>
                <td class="text-center"><?php echo e($row->payment_method); ?>, <?php echo e($row->sim_id); ?> <br> <?php echo e($row->accepted_by?? null); ?> </td>
                <!--<td class="text-center"><?php echo e(fake()->randomElement(['cashin', 'cashout', 'send money'])); ?></td>-->
                <td class="text-center"> <?php echo e($row->payment_method_trx); ?>



                </td>
                <td class="text-center"><?php echo e($make_method); ?>  </td>
                <td class="text-center"><?php echo e($row->amount); ?></td>
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
                    <?php echo e($row->created_at->format('h:i:sa, d-m-Y')); ?>,
                    <br>
                    <span class="text-success font-weight-bold"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(); ?></span>
                    <br>
                    <?php echo e($row->updated_at->format('h:i:sa, d-m-Y')); ?>,
                    <br>
                    <span class="text-success font-weight-bold"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans(); ?></span>
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
                <td>

                    <?php if($row->status == 0): ?>
                        <a href="#" data-payment-id="<?php echo e($row->id); ?>"
                            class=" rejectPaymentBtn openPopup btn btn-sm btn-outline-danger"><i
                                class="lni lni-cross-circle" aria-hidden="true"></i></a>
                        <button type="button" data-payment-id="<?php echo e($row->id); ?>"
                            class=" completePaymentBtn openPopup btn btn-sm btn-success"><i class="bx bx-check-double"
                                aria-hidden="true"></i></button>
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
                <th colspan="5">Total</th>
                <th><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo $data->links('common.pagination-custom'); ?>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/merchant/payment-request/data.blade.php ENDPATH**/ ?>
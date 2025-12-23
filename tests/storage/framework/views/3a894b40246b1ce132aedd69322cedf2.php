<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('mrc_content'); ?>
    <?php $__env->startPush('css'); ?>
        <?php

        use Carbon\Carbon;

        $today = Carbon::today();
        $total_modem = App\Models\Modem::where('db_status', 'live')->count();
        $total_agent = App\Models\User::where('db_status', 'live')->where('user_type', 'agent')->count();
        $total_trx_today = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])
            ->whereDate('sms_time', now())
            ->count();
        $total_trx_amount_today = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])
            ->whereDate('sms_time', now())
            ->sum('amount');
        $total_trx = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])->count();
        $total_trx_amount = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])->sum('amount');
        $total_pending = App\Models\BalanceManager::whereIn('status', [33, 55, 0])->count();
        $total_merchant = App\Models\Merchant::where('db_status', 'live')->count();
        $username = auth('merchant')->user()->username;
        $total_cashout = App\Models\BalanceManager::where('merchent_id', $username)
            ->whereIn('status', [20, 77])
            ->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])
            ->sum('amount');
        $today_cashout = App\Models\BalanceManager::where('merchent_id', $username)
            ->whereIn('status', [20, 77])
            ->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])
            ->whereDate('sms_time', $today)
            ->sum('amount');

        $merchant = Auth::guard('merchant')->user();






        if ($merchant->merchant_type == 'general') {

            $total_payment_request = App\Models\PaymentRequest::where('merchant_id', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('amount');

            $total_payment_request_today = App\Models\PaymentRequest::whereDate('created_at', now())
            ->where('merchant_id', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('amount');

            $total_payment_request_transection = App\Models\PaymentRequest::where('merchant_id', $merchant->id)->count();
            $total_mfs_request = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereIn('status', [2, 3])
                ->sum('amount');
            $today_total_mfs_request = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereDate('created_at', now())
                ->whereIn('status', [2, 3])
                ->sum('amount');

            $total_mfs_transection = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereIn('status', [1, 2])
                ->count();
        } else {

            $total_payment_request = App\Models\PaymentRequest::where('sub_merchant', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('amount');


            $total_payment_request_today = App\Models\PaymentRequest::whereDate('created_at', now())
            ->where('sub_merchant', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('amount');


            $total_payment_request_transection = App\Models\PaymentRequest::where('sub_merchant', $merchant->id)->count();
            $total_mfs_request = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereIn('status', [2, 3])
                ->sum('amount');
            $today_total_mfs_request = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereDate('created_at', now())
                ->whereIn('status', [2, 3])
                ->sum('amount');
            $total_mfs_transection = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereIn('status', [1, 2])
                ->count();
        }

        ?>
    <?php $__env->stopPush(); ?>

    <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h5 class="mb-2">Welcome, Merchant</h5>
                </div>
            </div>


            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 justify-content-center">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Current Balance</p>
                                    <h5 class="my-1">
                                        ৳<?php echo e($merchant->merchant_type == 'general' ? getMerchantBalance($merchant->id)['balance'] : subMerchantBalance($merchant->id)['balance']); ?>

                                    </h5>
                                    <p class="mb-0 font-12 text-info"><i class='bx bxs-up-arrow align-middle'></i>Active
                                        Balance</p>
                                </div>
                                <div class="widgets-icons bg-light-success text-success ms-auto">
                                    <i class='bx bxs-mobile'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Today Withdraw</p>
                                    <h5 class="my-1">৳ <?php echo e(money($today_total_mfs_request)); ?></h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Success + Approved</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Withdraw</p>
                                    <h5 class="my-1">৳ <?php echo e(money($total_mfs_request)); ?></h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Success + Approved(<?php echo e($total_mfs_transection); ?>)</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Pending Withdraw</p>
                                    <h5 class="my-1">
                                        ৳<?php echo e(money($merchant->merchant_type == 'general' ? getMerchantBalance($merchant->id)['totalPendingMfs'] : subMerchantBalance($merchant->id)['totalPendingMfs'])); ?>

                                    </h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Pending + waiting + Processing + Failed</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>



                

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Today Payment Request</p>
                                    <h5 class="my-1">৳<?php echo e(money($total_payment_request_today)); ?></h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Success + Approved</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Payment Request</p>
                                    <h5 class="my-1">৳<?php echo e(money($total_payment_request)); ?> </h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Success + Approved (<?php echo e($total_payment_request_transection); ?>)</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                






                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Pending Payment </p>
                                    <h5 class="my-1">
                                        ৳<?php echo e(money(getMerchantBalance($merchant->id)['totalPendingPayment'])); ?></h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Pending</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                


                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Receive Balance</p>
                                    <h5 class="my-1">৳<?php echo e(money(getMerchantBalance($merchant->id)['adminCreditAmount'])); ?>

                                    </h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Success + Approved</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Return Balance</p>
                                    <h5 class="my-1">৳<?php echo e(money(getMerchantBalance($merchant->id)['adminDebitAmount'])); ?>

                                    </h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only
                                        Success + Approved</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!--end row-->



    <!--<div class="card radius-10">-->
    <!--    <div class="card-body">-->
    <!--        <div class="d-flex align-items-center">-->
    <!--            <div>-->
    <!--                <h5 class="mb-0">Recent Transaction</h5>-->
    <!--            </div>-->

    <!--        </div>-->
    <!--        <hr/>-->
    <!--        <div class="table-responsive">-->
    <!--            <table class="table align-middle mb-0 table-bordered">-->
    <!--                <thead class="table-light">-->
    <!--                <tr>-->
    <!--                    <th>id</th>-->
    <!--                    <th scope="col" class="text-center">Sender</th>-->
    <!--                    <th scope="col">C Number</th>-->
    <!--                    <th scope="col">Oldbal</th>-->
    <!--                    <th scope="col">Amount</th>-->
    <!--                    <th scope="col">Comm.</th>-->
    <!--                    <th scope="col">Lastbal</th>-->
    <!--                    <th scope="col">Trxid</th>-->
    <!--                    <th scope="col">A Number</th>-->
    <!--                    <th scope="col">Status</th>-->
    <!--                    <th scope="col">Date</th>-->
    <!--                </tr>-->
    <!--                </thead>-->
    <!--                <tbody>-->

    <!--                <?php $lasttrx = App\Models\BalanceManager::orderBy('id', 'desc')->take(10)->get(); ?>-->

    <!--                <?php $__currentLoopData = $lasttrx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    -->
    <!--                    <tr>-->
    <!--                        <td><?php echo e($row->id); ?></td>-->
    <!--                        <td><?php echo e($row->sender); ?></td>-->
    <!--                        <td><?php echo e($row->mobile); ?></td>-->
    <!--                        <td class="text-end"><?php echo e(money($row->oldbal)); ?></td>-->
    <!--                        <td class="text-end"><?php echo e(money($row->amount)); ?></td>-->
    <!--                        <td><?php echo e(money($row->commission)); ?></td>-->
    <!--                        <td class="text-end"><?php echo e(money($row->lastbal)); ?></td>-->
    <!--                        <td><?php echo e($row->trxid); ?></td>-->
    <!--                        <td><?php echo e($row->sim); ?></td>-->

    <!--                        <?php if($row->status == 20 || $row->status == 22): ?>
    -->
    <!--                            <td><span class='badge badge-pill bg-success'><i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Success</span></td>-->
    <!--
<?php elseif($row->status == 33): ?>
    -->
    <!--                            <td><span class='badge badge-pill bg-info text-white'>Waiting</span></td>-->
    <!--
<?php elseif($row->status == 55): ?>
    -->
    <!--                            <td><span class='badge badge-pill bg-danger text-white'> <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Danger</span></td>-->
    <!--
<?php elseif($row->status == 66): ?>
    -->
    <!--                            <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>-->
    <!--
<?php elseif($row->status == 77): ?>
    -->
    <!--                            <td><span class='badge badge-pill bg-success text-white'> <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Approved</span></td>-->
<!--                        <?php else: ?>-->
    <!--                            <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>-->
    <!--
    <?php endif; ?>-->

    <!--                        <td>-->
    <!--                            <?php echo e($row->sms_time); ?>-->
    <!--                            <p class="text-success font-weight-bold">-->
    <!--                                    <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->sms_time))->diffForHumans(); ?>-->
    <!--                            </p>-->
    <!--                        </td>-->
    <!--                    </tr>-->
    <!--
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
    <!--                </tbody>-->
    <!--            </table>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->




<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>
    <script type="text/javascript"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('merchant.mrc_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/dashboard.blade.php ENDPATH**/ ?>
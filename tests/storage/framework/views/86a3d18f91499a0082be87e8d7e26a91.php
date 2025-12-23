<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

    <?php
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

    $total_payment_request = App\Models\PaymentRequest::whereIn('status', [1, 2])->sum('amount');
    $total_payment_request_today = App\Models\PaymentRequest::whereIn('status', [1, 2])
        ->whereDate('created_at', now())
        ->sum('amount');

    $total_mfs_request = App\Models\ServiceRequest::whereIn('status', [2, 3])->sum('amount');
    $today_total_mfs_request = App\Models\ServiceRequest::whereIn('status', [2, 3])
        ->whereDate('created_at', now())
        ->sum('amount');

    $total_payment_request_transection = App\Models\PaymentRequest::count();
    if ($total_payment_request_transection) {
        $total_payments_complete_transection = round((App\Models\PaymentRequest::where('status', 1)->count() * 100) / $total_payment_request_transection);
        $total_payments_pending_transection = round((App\Models\PaymentRequest::where('status', 0)->count() * 100) / $total_payment_request_transection);
        $total_payments_approved_transection = round((App\Models\PaymentRequest::where('status', 2)->count() * 100) / $total_payment_request_transection);
    }

    $total_mfs_transection = App\Models\ServiceRequest::count();
    if ($total_mfs_transection) {
        $total_mfs_complete_transection = round((App\Models\ServiceRequest::where('status', 2)->count() * 100) / $total_mfs_transection);
        $total_mfs_approved_transection = round((App\Models\ServiceRequest::where('status', 3)->count() * 100) / $total_mfs_transection);
        $total_mfs_pending_transection = round((App\Models\ServiceRequest::whereNotIn('status', [1, 2, 3, 4])->count() * 100) / $total_mfs_transection);
    }



    ?>

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Modem</p>
                            <h5 class="my-1"><?php echo e($total_modem); ?> </h5>
                            <p class="mb-0 font-12 text-info"><i class='bx bxs-up-arrow align-middle'></i>Only Active Modem
                            </p>
                        </div>
                        <div class="widgets-icons bg-light-success text-success ms-auto">
                            <i class='bx bxs-mobile'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

         <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Agent <?php echo e($total_agent); ?></p>
                            <h5 class="my-1">৳<?php echo e(money(allAgentBalance()['allAgentBalance'])); ?></h5>
                            <p class="mb-0 font-12 text-info"><i class='bx bxs-up-arrow align-middle'></i>Only Agent User
                            </p>
                        </div>
                        <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bxs-group'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Merchant <?php echo e(DB::table('merchants')->count()); ?></p>
                            <h5 class="my-1">৳<?php echo e(money(allMerchantBalance()['balance'])); ?></h5>
                            <p class="mb-0 font-12 text-info"><i class='bx bxs-up-arrow align-middle'></i>Only Agent User
                            </p>
                        </div>
                        <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bxs-group'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Today Payment Request</p>
                            <h5 class="my-1">৳<?php echo e(money($total_payment_request_today)); ?></h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-danger text-danger ms-auto">
                            <i class='bx bxs-wallet'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Payment Request</p>
                            <h5 class="my-1">৳<?php echo e(money($total_payment_request)); ?></h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success
                                + Approved (<?php echo e($total_payment_request_transection); ?>)</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Pending Payment</p>
                            <h5 class="my-1"><?php echo e(money(adminBalance()['totalPendingPayment'])); ?></h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Pending
                            </p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Today MFS Request</p>
                            <h5 class="my-1">৳<?php echo e(money($today_total_mfs_request)); ?> </h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total MFS Request</p>
                            <h5 class="my-1">৳<?php echo e(money($total_mfs_request)); ?> </h5>
                            <p class="mb-0 font-12 text-danger"><i class='bx bxs-down-arrow align-middle'></i>Only Success +
                                Approved (<?php echo e($total_mfs_transection); ?>) </p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary"> Pending MFS</p>
                            <h5 class="my-1">৳<?php echo e(money(adminBalance()['totalPendingMfs'])); ?></h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Pending
                                + waiting + Processing + Failed
                            </p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


         <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Add Balance Agent</p>
                            <h5 class="my-1"><?php echo e(money(allAgentBalance()['adminCreditAmount'])); ?></h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only
                                Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Agent Return</p>
                            <h5 class="my-1"><?php echo e(money(allAgentBalance()['adminDebitAmount'] )); ?></h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only
                                Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Add Balance Merchants</p>
                            <h5 class="my-1">৳<?php echo e(money(allMerchantBalance()['adminCreditAmount'] )); ?></h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only
                                Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Merchants Return </p>
                            <h5 class="my-1">৳<?php echo e(money(allMerchantBalance()['adminDebitAmount'] )); ?></h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only
                                Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row">
        <?php if(count(activeModem()['online_modems']) > 0): ?>
            <h6 class="text-success mb-2">Total Online Modems: <?php echo e(activeModem()['online_count']); ?> </h6>
            <?php $__currentLoopData = activeModem()['online_modems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card  w-100 border-0 shadow-sm bg-success">
                        <div class="card-body text-white d-flex flex-column justify-content-between align-items-start">
                            <div class="w-100 d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="card-title"><?php echo e($item->sim_number); ?></h5>
                                    <?php
                                        $data = $item->modem_details;
                                        preg_match('/Charge: (\d+)/', $data, $matches);
                                        $charge = $matches[1];
                                    ?>
                                    <?php $__currentLoopData = explode(',', $item->operator); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $data = DB::table('balance_managers')
                                                ->where('sender', $operator)
                                                ->where('sim', $item->sim_number)
                                                ->latest('created_at') // Assumes 'created_at' is the timestamp column
                                                ->first();

                                        ?>
                                        <p class="card-title"><?php echo e($data->lastbal ?? 0.0); ?></p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    // Calculate the width of the charge level rectangle based on the charge percentage
                                    $chargeWidth = ($charge / 100) * 38; // 38 is the max width for 100% charge
                                    $chargeColor = $charge > 20 ? '#FFFFFF' : '#ff5722'; // Green if charge > 20%, otherwise red
                                ?>

                                <div class="battery-icon">
                                    <svg width="50" height="25" viewBox="0 0 50 25" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1" y="4" width="42" height="17" fill="none" stroke="black" stroke-width="2" rx="3" ry="3"/>
                                        <rect x="43" y="8" width="5" height="9" fill="black"/>
                                        <rect x="3" y="6" width="<?php echo e($chargeWidth); ?>" height="13" fill="<?php echo e($chargeColor); ?>"/> <!-- Dynamic width and color -->
                                    </svg>
                                    <span><?php echo e($charge); ?>%</span>
                                </div>

                                </div>
                                <div class="bg-white p-2 rounded d-flex flex-column" style="border-radius: 8px;">
                                    <?php $__currentLoopData = explode(',', $item->operator); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $operator = trim($operator); // Trim any whitespace
                                            $imagePath = '';
                                            switch ($operator) {
                                                case 'bkash':
                                                    $imagePath = 'payments/bkash.png';
                                                    break;
                                                case 'nagad':
                                                    $imagePath = 'payments/nagad.png';
                                                    break;
                                                case 'rocket':
                                                    $imagePath = 'payments/rocket.png';
                                                    break;
                                                default:
                                                    $imagePath = 'payments/default.png'; // Fallback image
                                            }
                                        ?>
                                        <img src="<?php echo e(asset($imagePath)); ?>" alt="<?php echo e($operator); ?>"
                                            style="width: 40px; border-radius: 8px; margin-bottom: 8px;">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <h3> No modems are currently online. </h3>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <!--end row-->



    



    <?php echo $__env->make('admin.admin_chart', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>






<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/admin-dashboard.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startPush('css'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

    <?php if(session()->has('message')): ?>
        <div class="alert alert-success" id="alert_success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>

    <?php if(Session::has('alert')): ?>
        <div class="alert alert-danger"><?php echo e(Session::get('alert')); ?></div>
    <?php endif; ?>


    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Add API Payment Method </h5>
                    <hr>
                    <form class="row g-3" action="<?php echo e(route('payment.create_payment_mobile_banking')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="col-md-12">
                            <label for="rows">Select from MFS operator list</label>
                            <select class="form-control" name="mfs_name" id="rows" required>
                                <option value="" disabled selected>Select MFS operator</option>
                                <?php $__currentLoopData = App\Models\MfsOperator::mfsList(1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="rows">Select Type <span>*</span></label>
                            <select class="form-control" name="agent_type" id="rows" required>
                                <option value="" disabled selected>Select type</option>
                                <option value="personal">Personal</option>
                                <option value="merchant">Merchant</option>
                                <option value="agent">Agent</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="rows">Select Client <span>*</span></label>
                            <select class="form-control" name="member" id="agent_select" required>
                                <option disabled selected>Select Client</option>
                                <?php $__currentLoopData = App\Models\User::userList('1', 'agent')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->member_code); ?>"><?php echo e($user->fullname); ?>

                                        (<?php echo e($user->member_code); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="rows">Select Modem</label>
                            <select class="form-control" name="modems" id="modem_select" required>
                                <option value="" disabled selected>Select Modems</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Submit</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        $(document).ready(function() {

            $('#agent_select').select2({
                placeholder: 'Select an option',
                maximumSelectionLength: 2,
            });

            $('#modem_select').select2({
                placeholder: 'Select an option',
                maximumSelectionLength: 2,
            });


            $('#agent_select').on('change', function() {

                var member_code = $('#agent_select').val();
                console.log(member_code)

                var url = "<?php echo e(route('payment.agents_modems', ':id')); ?>";
                url = url.replace(':id', member_code);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {

                        var options = response;
                        console.log(options)
                        $.each(options, function(index, value) {
                            console.log(value.id);
                            
                            console.log(value.text);
                            $('#modem_select').append('<option value="' + value.text +
                                '">' + value.text + '</option>');
                        });

                        $("#modem_select").trigger("change");

                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });


            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/payment_method/create_mobile_banking_payment.blade.php ENDPATH**/ ?>
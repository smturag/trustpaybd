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
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Mobile Banking List</h6>
                <a class="ms-auto btn btn-sm btn-primary" href="<?php echo e(route('withdraw.mobile_banking_create_view')); ?>">
                    <i class="bx bx-plus mr-1"></i> Add Method
                </a>
            </div>
            <hr/>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <table id="data_table" class="display">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Operator Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($key + 1); ?></td>
                                <td><?php echo e($data->mfs_name); ?></td>
                                <td><?php echo e($data->type); ?></td>
                                <td>
                                    <input class="form-check-input" data-pm_id="<?php echo e($data->id); ?>" id="switch-button"
                                           type="checkbox" role="switch" id="flexSwitchCheckDefault1" name="pm_status"
                                           data-value="<?php echo e($data->status); ?>" <?php echo e($data->status == 1 ? 'checked' : ''); ?>>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('withdraw.destroy')); ?>" method="POST" id="deleteForm">
                                        <?php echo csrf_field(); ?>
                                        <input hidden name="id" value=" <?php echo e($data->id); ?>">
                                        <button onclick="confirmDelete()" type="button" class="btn btn-outline-danger px-5">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script>
        $(document).ready(function () {

            $('#data_table').DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0], // Disable sorting on the "SL" column
                    responsive: true,
                    select: true
                }]
            });

            $('#data_table').on('click', '.form-check-input', function () {
                var row = $(this).closest('tr');
                // var id = row.find('.id').text();
                var pm_id = $(this).data('pm_id');
                var status = $(this).data('value');
                var url = "<?php echo e(route('withdraw.edit_status')); ?>";
                // var checked = $(this).is(':checked');
                // console.log('ID:', pm_id, 'Checked:', checked);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        // Pass any data you want to send with the request
                        id: pm_id,
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' // Add CSRF token to the request
                    },
                    success: function (response) {
                        // Handle the response
                        // console.log(response);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle error case
                        console.log('Error:', error);
                    }
                });
            });
        })

        function confirmDelete() {
            if (confirm('Are you sure you want to delete this record?')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/withdraw_method/mobile-banking-list.blade.php ENDPATH**/ ?>
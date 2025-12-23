<?php $__env->startSection('title', 'Dashboard'); ?>


<?php $__env->startSection('mrc_content'); ?>

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
                <h6 class="mb-0 text-uppercase ps-3">Service Request List</h6>
                
            </div>
            <hr />

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
                    <form action="#" class="mb-5 justify-content-center" role="form" method="GET" id="search-form"
                        data-route="<?php echo e(route('merchant.service-request')); ?>" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="rows"><?php echo e(translate('show')); ?></label>
                                    <select class="form-control" name="rows" id="rows">
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="150">150</option>
                                        <option value="200">200</option>
                                        <option value="400">400</option>
                                        <option value="500">500</option>
                                    </select>
                                </div>
                            </div>

                            <!--<div class="col-md-2">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="member_code">Reference</label>-->
                            <!--        <input type="text" class="form-control" name="reference" id="reference"/>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="trxid">Transaction ID</label>
                                    <input type="text" class="form-control" name="trxid">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sender"><?php echo e(translate('mfs')); ?></label>

                                    <select class="form-control" name="mfs" id="mfs">
                                        <option value="">--Select--</option>
                                        <option value="NAGAD">NAGAD</option>
                                        <option value="bKash">bKash</option>
                                        <option value="16216">Rocket</option>
                                        <option value="upay">upay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date1">Date From</label>
                                    <input type="date" class="form-control datepicker" name="from" id="date1"
                                        placeholder="Date From" size="18" value="<?php echo $from; ?>"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date2">Date To</label>
                                    <input type="date" class="form-control datepicker" name="to" id="date2"
                                        placeholder="Date To" size="18" value="<?php echo $to; ?>" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status"><?php echo e(translate('status')); ?></label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">--Status--</option>
                                        <option value="success">Success</option>
                                        <option value="waiting">Waiting</option>
                                        <option value="pending">Pending</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="processing">Processing</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>


                            

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cNumber"><?php echo e(translate('Customer Number')); ?></label>
                                    <input type="text" class="form-control" name="cNumber" id="cNumber">
                                </div>
                            </div>

                            <!--<div class="col-md-2">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="trxid">Transaction ID</label>-->
                            <!--        <select class="form-control" name="trxid" id="trxid">-->
                            <!--            <option value="">--type--</option>-->
                            <!--            <option value="partner">Partner</option>-->
                            <!--            <option value="dso">DSO</option>-->
                            <!--            <option value="agent">Agent</option>-->

                            <!--        </select>-->
                            <!--    </div>-->
                            <!--</div>-->


                            <!--<div class="col-md-4">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="message">Mobile/Email/Name</label>-->
                            <!--        <input type="text" class="form-control" name="message" id="message">-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="col-md-2">
                                <div class="form-group d-grid">
                                    <label class="" for="">&nbsp;</label>
                                    <button type="submit" class="btn btn-danger btn-block"><span
                                            class="fa fa-search"></span> <?php echo e(translate('Search')); ?> </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="tableContentWrap">
                        <?php echo $__env->make('merchant.service-request.data', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="2" />
                    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    

    <script>
        $(document).ready(function() {
            $('#search-btn').click(function() {
                var form = $('#search-form');
                var route = form.data('route');
                var formData = form.serialize();

                $.ajax({
                    url: route,
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('#tableContentWrap').html(response);
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('merchant.mrc_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/service-request/request-list.blade.php ENDPATH**/ ?>
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="largemodal1"><?php echo e($request_data->merchant->username); ?> -<?php echo e($request_data->mfs); ?> -
            <?php echo e($request_data->number); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <form action="#" id="approved_save" method="post">
            <?php echo csrf_field(); ?>

            <table class="table table-bordered table-sm">
                <tr>
                    <th><?php echo e(translate('sender')); ?></th>
                    <td><?php echo e($request_data->merchant->username); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(translate('customer_number')); ?></th>
                    <td><?php echo e($request_data->number); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(translate('oldbal')); ?></th>
                    <td><?php echo e(money($request_data->old_balance)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(translate('amount')); ?></th>
                    <td><?php echo e(money($request_data->amount)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(translate('mfs')); ?></th>
                    <td><?php echo e($request_data->mfs); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(translate('type')); ?></th>
                    <td><?php echo e($request_data->type); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(translate('lastbal')); ?></th>
                    <td><?php echo e(money($request_data->new_balance)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(translate('trxid')); ?></th>
                    <td><?php echo e($request_data->trxid); ?></td>
                </tr>

            </table>

            <p>SMS Body: <?php echo e($request_data->msg); ?></p>

            <div class="form-group">
                <label for="lastbal">TRX ID</label>
                <input type="text" name="get_trxid" id="get_trxid" class="form-control"
                    value="<?php echo e($request_data->get_trxid); ?>">
                <input type="hidden" id="id" name="id" value="<?php echo e($request_data->id); ?>">
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><?php echo e(translate('Update')); ?></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("form#approved_save").submit(function(event) {
            event.preventDefault();

            var _token = "<?php echo e(csrf_token()); ?>";
            var get_trxid = $(this).find('input#get_trxid').val();
            var id = $(this).find('input#id').val();

            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('approved_save')); ?>",
                data: {
                    _token: _token,
                    id: id,
                    get_trxid: get_trxid
                },
                success: function(response) {
                    if (response.status === 200) {
                        $("table#req_table").find('tr#' + id).css('background',
                            'antiquewhite');
                        $("table#req_table").find('tr#' + id).find('span.badge').attr(
                            'class', 'badge badge-pill badge-success text-white').text(
                            'Approved');
                        $('#myModal').modal('hide');
                    }
                },
            });
            return false;
        });
    });
</script>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/mfs/req-approved.blade.php ENDPATH**/ ?>
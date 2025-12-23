<?php $__env->startSection('title', 'Sms Inbox'); ?>

<?php $__env->startSection('member_content'); ?>

<?php $__env->startPush('css'); ?>
 <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />  
<?php $__env->stopPush(); ?>
  
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="header d-none d-sm-flex align-items-center mb-3">
                        <h6 class="mb-0 text-uppercase ps-3">Inbox List</h6>
                    </div>
                    <hr/>

                   
                    <div class="card">
                        <div class="card-body">
                            <form action="#" class="mb-4" role="form" method="post" id="search-form" accept-charset="utf-8">
                                <div class="row g-2">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="rows">Show</label>
                                            <select class="form-control" name="rows" id="rows">
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="150">150</option>
                                                <option value="200">200</option>
                                                <option value="400">400</option>
                                                <option value="500">500</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="sender"><?php echo e(translate('sim')); ?></label>
                                            <input type="text" class="form-control" name="sim_number" id="sim_number">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="message"><?php echo e(translate('message')); ?></label>
                                            <input type="text" class="form-control" name="message" id="message">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="service">Sender</label>
                                            <select class="form-control" name="sender" id="sender">
											  <option value="">--Any--</option>
	
                                                <option value="bKash">bKash</option>
                                                <option value="NAGAD">NAGAD</option>
                                                <option value="16216">Rocket</option>
												
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date1">Date From</label>
                                            <input type="text" class="form-control datepicker" name="from" id="date1" placeholder="Date From" size="18" value="<?php echo $from;?>" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="date2">Date To</label>
                                            <input type="text" class="form-control datepicker" name="to" id="date2" placeholder="Date To" size="18" value="<?php echo $to;?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group d-grid">
                                            <label class="" for="">&nbsp;</label>
                                            <button type="submit" class="btn btn-danger btn-block"><span class="fa fa-search"></span> <?php echo e(translate('Search')); ?> </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive" id="tableContentWrap">
                                <?php echo $__env->make('member.member_sms.inbox_table_content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
      
<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>
    <script type="text/javascript">
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    fetchData(page);
                }
            }
        });

        $(document).ready(function () {
            $(document).on('submit', '#search-form', function (e) {
                e.preventDefault();
                $("button[type=submit]").attr("disabled", "disabled");
                $("button[type=submit]").empty().html("Please wait...");
                var page = $('#hidden_page').val();

                fetchData(page);
            });

            function fetchData(page) {
                $.ajax({
                    url: '?page=' + page,
                    data: $('#search-form').serialize(),
                    type: "get",
                    datatype: "html"
                })
                .done(function (data) {
                    $("button[type=submit]").removeAttr('disabled');
                    $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
                    $("#tableContentWrap").empty().html(data);
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }

            $(document).on('click', '.sorting', function () {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';
                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    $('#' + column_name + '').html('<span class="fa fa-angle-down"></span>');
                }
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    $('#' + column_name + '').html('<span class="fa fa-angle-up"></span>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();

                fetchData(page);
            });

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                $('li').removeClass('active');
                $(this).parent().addClass('active');

                var page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });
        });
    </script>
	
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

	<script>
		
		$(".datepicker").flatpickr();

		$(".time-picker").flatpickr({
				enableTime: true,
				noCalendar: true,
				dateFormat: "Y-m-d H:i",
			});

		$(".date-time").flatpickr({
				enableTime: true,
				dateFormat: "Y-m-d H:i",
		});

		$(".date-format").flatpickr({
			altInput: true,
			altFormat: "F j, Y",
			dateFormat: "Y-m-d",
		});

		$(".date-range").flatpickr({
			mode: "range",
			altInput: true,
			altFormat: "F j, Y",
			dateFormat: "Y-m-d",
		});

		$(".date-inline").flatpickr({
			inline: true,
			altInput: true,
			altFormat: "F j, Y",
			dateFormat: "Y-m-d",
		});

	</script>
	
<?php $__env->stopPush(); ?>

<?php echo $__env->make('member.layout.member_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/member_sms/list.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('css'); ?>
        <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <?php $__env->stopPush(); ?>

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-none d-sm-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Modem List</h6>
            </div>
            <hr />



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


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sender"><?php echo e(translate('sim')); ?></label>
                                    <input type="text" class="form-control" name="sim_number" id="sender">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sender"><?php echo e(translate('agent_code')); ?></label>
                                    <input type="text" class="form-control" name="member_code" id="member_code">
                                </div>
                            </div>




                            <!-- <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="date1">Date From</label>
                                                                    <input type="text" class="form-control datepicker" name="from" id="date1" placeholder="Date From" size="18" value="<?php echo $from; ?>" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label for="date2">Date To</label>
                                                                    <input type="text" class="form-control datepicker" name="to" id="date2" placeholder="Date To" size="18" value="<?php echo $to; ?>" autocomplete="off">
                                                                </div>
                                                            </div>-->

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sender"><?php echo e(translate('merchant_code')); ?></label>
                                    <input type="text" class="form-control" name="merchant_code" id="merchant_code">
                                </div>
                            </div>

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

                        <?php echo $__env->make('admin.admin_modem.modem_table_content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modalbody">
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>
    <script type="text/javascript">
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    fetchData(page);
                }
            }
        });

        function delete_record(id, title) {
            var button = $('table').find("tr#" + id).find(".delete");

            button.attr("disabled", "disabled");
            button.empty().html("Deleting...");

            swal({
                    title: "Are you sure?",
                    text: "You will Request " + title + " ?",
                    icon: "warning",
                    // showCancelButton: true,
                    // confirmButtonColor: "#DD6B55",
                    // confirmButtonText: "Yes, " + title + " it!",
                    // closeOnConfirm: false,
                    buttons: {
                        cancel: true,
                        confirm: 'Confirm Delete',
                    },
                    dangerMode: true,
                })
                .then((isConfirm) => {
                    if (isConfirm) {
                        $.ajax({
                            url: "<?php echo e(url('admin/modem_delete/')); ?>/" + id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "post",
                            data: {
                                id: id,
                                _method: "DELETE"
                            },
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                swal("Done!", "It was successfully " + title, "success");
                                $('table').find('tr#' + id).animate({
                                    backgroundColor: "#e74c3c",
                                    color: "#fff"
                                }, "slow").animate({
                                    opacity: "hide"
                                }, "slow");
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Error adding / update data');
                            }
                        });
                        //reloadfetchdata(page, sort_type, column_name);
                    } else {
                        swal("Cancelled", "Your imaginary file is safe :)", "error");
                        button.removeAttr("disabled");
                        button.empty().html("Delete");
                    }
                });
        }

        $(document).ready(function() {

            $(document).on('click', '.openPopup', function(event) {
                var id = $(this).attr('id');
                var dataURL = "<?php echo e(url('/admin/modem_set_merchant')); ?>/" + id;
                event.preventDefault();

                //console.log("Hello world!" + dataURL);

                $('.modalbody').load(dataURL, function() {
                    $('#myModal').modal({
                        show: true
                    });
                });
            });

            $(document).on('submit', '#search-form', function(e) {
                e.preventDefault();
                $("button[type=submit]").attr("disabled", "disabled");
                $("button[type=submit]").empty().html("Please wait...");

                //var query = $('#serach').val();
                var page = $('#hidden_page').val();

                fetchData(page);
            });

            function fetchData(page) {
                $.ajax({
                        url: '?page=' + page,
                        //url:"!! route('balance_manager_filter') !!}?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type,
                        data: $('#search-form').serialize(),
                        type: "get",
                        datatype: "html"
                    })
                    .done(function(data) {
                        $("button[type=submit]").removeAttr('disabled');
                        $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
                        $("#tableContentWrap").empty().html(data);
                        //location.hash = page;
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
                        alert('No response from server');
                    });
            }

            $(document).on('click', '.sorting', function() {
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

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                $('li').removeClass('active');
                $(this).parent().addClass('active');

                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                //var query = $('#serach').val();

                fetchData(page, sort_type, column_name);
            });
        });

        function changeOperatingStatus(modemId, status) {
            const url = `/admin/modem_operating_status/${modemId}/${status}`;

            swal({
                title: "Are you sure?",
                text: "Do you want to change the modem's operating status?",
                icon: "warning",
                buttons: {
                    cancel: "Cancel",
                    confirm: {
                        text: "Yes, change it!",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                },
                dangerMode: true,
            }).then((willChange) => {
                if (willChange) {
                    // Proceed with the AJAX request if the user confirms
                    $.ajax({
                        url: url,
                        type: 'GET', // or 'POST' if your route expects a POST request
                        success: function(response) {
                            if (response.status === 200) {
                               location.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                'Failed to change modem status.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
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

<?php echo $__env->make('admin.layouts.admin_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/admin_modem/modem_list.blade.php ENDPATH**/ ?>
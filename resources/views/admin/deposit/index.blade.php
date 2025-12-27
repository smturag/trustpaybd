@extends('admin.layouts.admin_app')
@section('title', 'Merchant Deposit Requests')

@section('content')
<div class="container-fluid py-4">

    <div class="card mb-4 shadow-sm">
        <div class="card-header  text-white">
            <h5 class="mb-0">Deposit Request List

            <hr>
            
            </h5>
            
        </div>

        {{-- Filters --}}
        <div class="card-body">
            <form id="filter-form" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Show</label>
                    <select class="form-select" name="rows">
                        <option value="10">10</option>
                        <option value="50" selected>50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Transaction ID</label>
                    <input type="text" class="form-control" name="trxid" placeholder="Enter ID">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="success">Success</option>
                        <option value="3">Rejected</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Customer Name/Number</label>
                    <input type="text" class="form-control" name="cust_name" placeholder="Enter name or number">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Method Number</label>
                    <input type="text" class="form-control" name="method_number" placeholder="Enter method">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Reference</label>
                    <input type="text" class="form-control" name="reference" placeholder="Reference">
                </div>

                <div class="col-md-2">
                    <label class="form-label">MFS</label>
                    <select class="form-select" name="mfs">
                        <option value="">--Select--</option>
                        <option value="nagad">NAGAD</option>
                        <option value="bkash">bKash</option>
                        <option value="16216">Rocket</option>
                        <option value="upay">upay</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Payment Type</label>
                    <select class="form-select" name="payment_type">
                        <option value="">All</option>
                        <option value="P2A">P2A - Cash Out</option>
                        <option value="P2P">P2P - Send Money</option>
                        <option value="P2C">P2C - Payment</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Merchant</label>
                    <select class="form-select select2" name="merchant_id">
                        <option value="">All</option>
                        @foreach($merchants as $merchant)
                            <option value="{{ $merchant->id }}">{{ $merchant->fullname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date">
                </div>

                <div class="col-md-2">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100 mt-3">Search</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DataTable --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="deposit_table">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Method</th>
                            <th>MFS Method/Trx</th>
                            <th>Amount</th>
                            <th>Fee / Comm</th>
                            <th>Balance Change</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deposit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><th>Payment ID</th><td id="detail_payment_id"></td></tr>
                            <tr><th>Request ID</th><td id="detail_request_id"></td></tr>
                            <tr><th>TRX ID</th><td id="detail_trxid"></td></tr>
                            <tr><th>Name</th><td id="detail_merchant_name"></td></tr>
                            <tr><th>Designation</th><td id="detail_designation"></td></tr>
                            <tr><th>Customer Phone</th><td id="detail_cust_phone"></td></tr>
                            <tr><th>Reference</th><td id="detail_reference"></td></tr>
                            <tr><th>Payment Method</th><td id="detail_payment_method"></td></tr>
                            <tr><th>Payment Type</th><td id="detail_payment_type"></td></tr>
                            <tr><th>Payment Trx</th><td id="detail_payment_trx"></td></tr>
                            <tr><th>Amount</th><td id="detail_amount"></td></tr>
                            <tr><th>Fee</th><td id="detail_fee"></td></tr>
                            <tr><th>Commission</th><td id="detail_commission"></td></tr>
                            <tr><th>Balance Change</th><td id="detail_balance_change"></td></tr>
                            <tr><th>From Number</th><td id="detail_from_number"></td></tr>
                            <tr><th>Note</th><td id="detail_note"></td></tr>
                            <tr><th>Status</th><td id="detail_status"></td></tr>
                            <tr><th>Accepted By</th><td id="detail_accepted_by"></td></tr>
                            <tr><th>Callback URL</th><td id="detail_callback_url"></td></tr>
                            <tr><th>Webhook URL</th><td id="detail_webhook_url"></td></tr>
                            <tr><th>Created At</th><td id="detail_created_at"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reject_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-modal">
        <div class="modal-content">
            <form id="reject_form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Transaction Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" name="transId" id="modal_id">
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reject Reason</label>
                        <input id="reason" type="text" name="reason" class="form-control" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="spamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="spamForm">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Approve Payment Request</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="payment_id" id="spam_payment_id">
            
            <div class="alert alert-info">
                <i class="bx bx-info-circle"></i>
                <strong>Note:</strong> Fees and commissions will be automatically calculated based on the merchant's configured rates for the selected MFS operator.
            </div>
            
            <div class="mb-3">
                <label for="mfs_operator_id" class="form-label">MFS Operator <span class="text-danger">*</span></label>
                <select class="form-select" name="mfs_operator_id" id="spam_mfs_operator_id" required>
                    <option value="">Select MFS Operator</option>
                    @foreach($mfsOperators as $operator)
                        <option value="{{ $operator->id }}" data-name="{{ $operator->name }}" data-type="{{ $operator->type }}">
                            {{ $operator->name }} ({{ $operator->type }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label for="sim_id" class="form-label">SIM ID / Method Number (Optional)</label>
                <input type="text" class="form-control" name="sim_id" id="spam_sim_id" placeholder="Enter SIM ID or method number">
                <small class="text-muted">Leave empty to keep the existing SIM ID</small>
            </div>
            
            <div class="mb-3">
                <label for="payment_method_trx" class="form-label">Payment Method Trx <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="payment_method_trx" id="spam_payment_trx" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (Optional)</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="spam_amount">
                <small class="text-muted">Leave empty to keep the original amount</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>




@endsection

@push('css')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .dataTables_wrapper .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: auto;
        padding: 10px 30px;
        background: rgba(0,0,0,0.7);
        color: #fff;
        font-weight: bold;
        border-radius: 5px;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .btn-approve { background-color: #28a745; color: #fff; }
    .btn-reject { background-color: #dc3545; color: #fff; }
    
    /* Table row borders for better separation */
    #deposit_table.dataTable tbody tr {
        border-bottom: 1px solid #dee2e6 !important;
    }
    
    #deposit_table.dataTable tbody td {
        border-bottom: 1px solid #dee2e6 !important;
    }
    
    #deposit_table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    
    /* Action column styling */
    #deposit_table tbody td:last-child {
        text-align: center;
        white-space: normal;
        padding: 6px 4px !important;
        min-width: 80px;
        max-width: 90px;
    }
    
    #deposit_table tbody td:last-child .btn {
        display: block;
        width: 100%;
        margin-bottom: 3px;
        padding: 3px 6px;
        font-size: 10px;
        line-height: 1.3;
        border-radius: 3px;
    }
    
    #deposit_table tbody td:last-child .btn:last-child {
        margin-bottom: 0;
    }
    
    #deposit_table tbody td:last-child .btn i {
        font-size: 11px;
        vertical-align: middle;
    }
    
    /* Hide button text on icon-only buttons */
    #deposit_table tbody td:last-child .btn-outline-primary .bx-show,
    #deposit_table tbody td:last-child .btn-outline-danger .lni-cross-circle,
    #deposit_table tbody td:last-child .btn-outline-danger .bx-hourglass {
        margin: 0;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Serialize form to object
    $.fn.serializeObject = function(){
        let obj = {};
        let arr = this.serializeArray();
        $.each(arr, function() {
            if(obj[this.name] !== undefined){
                if(!Array.isArray(obj[this.name])) obj[this.name] = [obj[this.name]];
                obj[this.name].push(this.value || '');
            } else obj[this.name] = this.value || '';
        });
        return obj;
    };

    // Initialize DataTable
    let table = $('#deposit_table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50, // default rows
        ajax: {
            url: "{{ route('deposit') }}",
            data: function(d){
                return $.extend({}, d, $('#filter-form').serializeObject());
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false},
            {data: 'merchant_name', name: 'merchant_name'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'payment_method_trx', name: 'payment_method_trx'},
            {data: 'amount', name: 'amount'},
            {data: 'fee_commission', name: 'fee_commission', orderable:false, searchable:false},
            {data: 'balance_change', name: 'balance_change', orderable:false, searchable:false},
            {data: 'dates', name: 'dates'},
            {data: 'status_html', name: 'status', orderable:false, searchable:false},
            {data: 'action', name: 'action', orderable:false, searchable:false}
        ],
        language: {
            processing: "<span>Please wait...</span>"
        },
        preDrawCallback: function(settings) {
            // Disable button while table is loading
            $('#filter-form button[type="submit"]').prop('disabled', true).text('Please wait...');
        },
        drawCallback: function(settings) {
            // Re-enable button after table has loaded
            $('#filter-form button[type="submit"]').prop('disabled', false).text('Search');
        }
    });

    // Filter form submit
    $('#filter-form').on('submit', function(e){
        e.preventDefault();
        table.ajax.reload();
    });

    // Select2 init
    $('.select2').select2({width: '100%'});
});


$(document).on('click', '.rejectPaymentBtn', function() {
    const paymentId = $(this).data('payment-id');
    $('#modal_id').val(paymentId);
    $('#reason').val(''); // clear previous reason
    const modal = new bootstrap.Modal(document.getElementById('reject_modal'));
    modal.show();
});

$(document).on('click', '.viewPaymentBtn', function() {
    const data = this.dataset;
    const setDetail = (id, value) => $(id).text(value || '-');

    setDetail('#detail_payment_id', data.paymentId);
    setDetail('#detail_request_id', data.requestId);
    setDetail('#detail_trxid', data.trxid);
    setDetail('#detail_merchant_name', data.merchantName);
    setDetail('#detail_designation', data.designation);
    setDetail('#detail_cust_phone', data.custPhone);
    setDetail('#detail_reference', data.reference);
    setDetail('#detail_payment_method', data.paymentMethod);
    setDetail('#detail_payment_type', data.paymentType);
    setDetail('#detail_payment_trx', data.paymentTrx);
    setDetail('#detail_amount', data.amount);
    setDetail('#detail_fee', data.fee);
    setDetail('#detail_commission', data.commission);
    $('#detail_balance_change').html(data.balanceChange || '-');
    setDetail('#detail_from_number', data.fromNumber);
    setDetail('#detail_note', data.note);
    setDetail('#detail_status', data.status);
    setDetail('#detail_accepted_by', data.acceptedBy);
    setDetail('#detail_callback_url', data.callbackUrl);
    setDetail('#detail_webhook_url', data.webhookUrl);
    setDetail('#detail_created_at', data.createdAt);

    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
});

$('#reject_form').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);
    let button = form.find('button[type="submit"]');
    button.attr('disabled', true).text('Submitting...');

    let formData = new FormData(this); // automatically includes CSRF
    // Make sure transId is included
    formData.set('transId', $('#modal_id').val());

    $.ajax({
        url: "{{ route('reject_deposit_request') }}",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.status === 200) {
                // Close modal
                $('#reject_modal').modal('hide');

                let table = $('#deposit_table').DataTable();
                let row = table.rows().nodes().to$().find(`button[data-payment-id="${$('#modal_id').val()}"]`).closest('tr');
                if (row.length) {
                    $(row).find('td:nth-child(7)').html("<span class='badge bg-danger text-white'>Rejected</span>");
                    $(row).find('td:nth-child(8)').html(""); // remove action buttons
                }

                swal("Success", res.message, "success");
            } else {
                swal("Error", res.message || 'Something went wrong!', "error");
            }

            button.removeAttr('disabled').text('Submit');
        },
        error: function(xhr) {
            let message = "Unknown error";
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                message = Object.values(xhr.responseJSON.errors).map(e => e[0]).join("\n");
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                message = xhr.responseText;
            }

            swal("Error " + xhr.status, message, "error");
            button.removeAttr('disabled').text('Submit');
        }
    });
});

// Reset modal buttons when opened
$('#reject_modal').on('show.bs.modal', function () {
    let button = $(this).find('button[type="submit"]');
    button.removeAttr("disabled").text("Submit");
    $(this).find('form')[0].reset();
});

// Remove the show.bs.modal reset handler - it was clearing values set by spamPaymentBtn
// Reset is now handled when modal is hidden instead

$(document).on('click', '.spamPaymentBtn', function() {
    let paymentId = $(this).data('payment-id');
    let simId = $(this).data('sim-id') || '';
    let paymentMethod = $(this).data('payment-method') || '';
    let paymentType = $(this).data('payment-type') || '';
    let paymentTrx = $(this).data('payment-trx') || '';
    let amount = $(this).data('amount') || '';
    
    // Debug: Log values to console
    console.log('Payment ID:', paymentId);
    console.log('SIM ID:', simId);
    console.log('Payment Method:', paymentMethod);
    console.log('Payment Type:', paymentType);
    console.log('Payment Trx:', paymentTrx);
    console.log('Amount:', amount);
    
    // Show modal first
    $('#spamModal').modal('show');
    
    // Then set values after a short delay to ensure modal is rendered
    setTimeout(function() {
        $('#spam_payment_id').val(paymentId);
        $('#spam_sim_id').val(simId);
        $('#spam_payment_trx').val(paymentTrx);
        $('#spam_amount').val(amount);
        
        // Set MFS operator based on payment method and type
        if (paymentMethod && paymentType) {
            let operatorFound = false;
            $('#spam_mfs_operator_id option').each(function() {
                let optionName = $(this).data('name');
                let optionType = $(this).data('type');
                if (optionName && optionType && 
                    optionName.toLowerCase() === paymentMethod.toLowerCase() && 
                    optionType.toLowerCase() === paymentType.toLowerCase()) {
                    $('#spam_mfs_operator_id').val($(this).val());
                    operatorFound = true;
                    console.log('MFS Operator found and set:', $(this).val());
                    return false; // break loop
                }
            });
            
            if (!operatorFound) {
                $('#spam_mfs_operator_id').val('');
                console.log('MFS Operator not found');
            }
        } else {
            $('#spam_mfs_operator_id').val('');
        }
        
        console.log('Form values set successfully');
    }, 100);
});

// Submit Spam form via AJAX
$('#spamForm').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);
    let button = form.find('button[type="submit"]');

    button.attr("disabled", true).html("Submitting...");

    $.ajax({
        url: "{{ route('approve_deposit_request') }}",
        method: "POST",
        data: form.serialize(),
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res.success) {
                $('#spamModal').modal('hide');
                let table = $('#deposit_table').DataTable();
                let row = table.rows().nodes().to$().find(`button[data-payment-id="${res.payment_id}"]`).closest('tr');
                if (row.length) {
                    $(row).find('td:nth-child(7)').html("<span class='badge bg-success text-white'>Approved</span>");
                    $(row).find('td:nth-child(8)').html("");
                    $(row).css('background-color', '#d4edda').animate({ backgroundColor: '' }, 2000);
                }
                swal("Success", res.message, "success");
            } else {
                swal("Error", res.message, "error");
            }
            button.removeAttr("disabled").html("Submit");
        },
        error: function(xhr, status, err) {
            console.error("XHR:", xhr);
            console.error("Status:", status);
            console.error("Error:", err);

            let message = "Unknown error";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                message = xhr.responseText;
            }

            swal("Error " + xhr.status, message, "error");
            button.removeAttr("disabled").html("Submit");
        }
    });
});

$('#filter-form').on('submit', function(e){
    e.preventDefault();

    let button = $(this).find('button[type="submit"]');
    button.prop('disabled', true).text('Please wait...');

    $('#deposit_table').DataTable().ajax.reload(function(){
        // Re-enable after reload
        button.prop('disabled', false).text('Search');
    });
});



</script>
@endpush

@extends('customer-panel.customer_app')
@section('title', 'Dashboard')

@push('css')
@endpush

@section('customer_content')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Send Money</div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Send Money</h5>
                        <form class="row g-3" id="send_money" action="{{ route('customer.submit_send_money') }}"
                            method="POST">

                            @csrf
                            <div id="choose_Customer">
                                <div class="col-md-12">
                                    <label for="input1" class="form-label">Choose Customer</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Select by email or mobile" required>
                                    <span class="error" id="error_input1"></span>
                                </div>

                                <div>
                                    <input type="text" value="" name="receiver_id" id="receiver" hidden>
                                    <input type="text" value="" name="charge_amount" id="c_amount" hidden>
                                    <input type="text" value="" name="send_amount" id="s_amount" hidden>
                                </div>

                                <div class="col-md-12">
                                    <label for="input2" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount"
                                        placeholder="Please Input Your Amount" name="amount" required>
                                    <span class="error" id="error_amount"></span>
                                </div>
                                <div class="col-md-12">
                                    <label for="input3" class="form-label">Note</label>
                                    <textarea class="form-control" name="note" id="note" cols="30" rows="5" required></textarea>
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="button" onclick="choose_customer(0)"
                                            class="btn btn-primary px-4">Submit</button>
                                        <button type="button" class="btn btn-light px-4">Reset</button>
                                    </div>
                                </div>

                            </div>

                            <div id="confirm" style="display: none">
                                <div class="p-4">
                                    <div class="d-flex flex-wrap">
                                        <div>
                                            <p class="font-weight-600">
                                                You are sending money to
                                            <p id="show_email"><span style="font-weight: bold;"></span></p>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h6 class="sub-title">Details</h6>
                                        <hr>
                                    </div>

                                    <div>
                                        <div class="d-flex flex-wrap justify-content-between mt-2">
                                            <div>
                                                <p>Transfer Amount</p>
                                            </div>

                                            <div class="pl-2">
                                                <p id='transfer_amount'>৳ 2.00</p>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap justify-content-between mt-2">
                                            <div>
                                                <p>Fee</p>
                                            </div>

                                            <div class="pl-2">
                                                <p id='charge'>৳ 0.00</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mb-2">

                                    <div class="d-flex flex-wrap justify-content-between">
                                        <div>

                                            <p class="font-weight-600">Total</p>
                                        </div>

                                        <div class="pl-2">
                                            <p class="font-weight-600" id='total'>৳ 2.00</p>
                                        </div>
                                    </div>


                                    <div class="row m-0 mt-4 justify-content-between">
                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                                                <button type="button" onclick="choose_customer(1)"
                                                    class="btn btn-light px-4">Back</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>


                <!--end row-->


            </div><!--end row-->




        </div>


        <script>
            const name = document.getElementById('name');
            const amount = document.getElementById('amount');
            const note = document.getElementById('note');
            const getEmailTag = document.getElementById('show_email');
            const getTransferTag = document.getElementById('transfer_amount');
            const getChargeTag = document.getElementById('charge');
            const getTotalTag = document.getElementById('total');
            const getReceiver = document.getElementById('receiver');
            const charge_amount = document.getElementById('charge_amount');
            const send_amount = document.getElementById('s_amount');

            let checking_customer = false;
            let checking_amount = false;


            if (!name.value) {
                document.getElementById('error_input1').textContent = '';
            }

            if (!amount.value) {
                document.getElementById('error_amount').textContent = '';
            }

            name.addEventListener('input', function() {
                const value = name.value;
                if (!name.value) {
                    document.getElementById('error_input1').textContent = '';
                }

                var url = "{{ route('customer.find', ['letter' => ':letter']) }}";
                url = url.replace(':letter', value);



                if (name.value) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function(data) {
                            // Handle the response data
                            if (data != 1) {
                                document.getElementById('error_input1').textContent = 'Data not found';
                                checking_customer = false;
                            } else {
                                document.getElementById('error_input1').textContent = 'user exist';
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle errors if any
                            console.error("Error:", error);
                        }
                    });
                }
            })


            amount.addEventListener('input', function() {
                const getAmount = amount.value
                console.log(getAmount)

                var url = "{{ route('customer.amount_check', ['amount_qty' => ':amount']) }}";
                url = url.replace(':amount', getAmount);

                if (getAmount) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function(data) {

                            if (data != 1) {
                                document.getElementById('error_amount').textContent =
                                    'Amount larger than your balance';
                                checking_amount = false
                            } else {
                                document.getElementById('error_amount').textContent = '';
                                checking_amount = true
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle errors if any
                            console.error("Error:", error);
                        }
                    });
                }

            })

            function choose_customer(params) {

                var myDiv = document.getElementById("choose_Customer");
                var confirm = document.getElementById("confirm");

                if (params == 0) {
                    if (checking_customer == true && checking_amount == true && note.value) {
                        var myDiv = document.getElementById("choose_Customer");
                        var confirm = document.getElementById("confirm");
                        myDiv.style.display = "none";
                        confirm.style.display = "block";
                        getEmailTag.textContent = name.value
                        getTransferTag.textContent = amount.value
                        getChargeTag.textContent = 0.0
                        getTotalTag.textContent = parseInt(amount.value)
                        // send_amount.value = parseInt(amount.value)

                    }
                } else if (params == 1) {
                    confirm.style.display = "none";
                    myDiv.style.display = "block";

                }
            }

            name.addEventListener("blur", function() {
                const value = name.value;

                var url = "{{ route('customer.customer_check', ['name' => ':name']) }}";
                url = url.replace(':name', value);

                if (value) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function(data) {
                            // Handle the response data
                            console.log(data)
                            if (data == false) {
                                confirm("This user is not available");
                                name.value = ''
                                document.getElementById('error_input1').textContent = '';
                                checking_customer = false;
                            } else {
                                getReceiver.value = data.id;
                                checking_customer = true;
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle errors if any
                            console.error("Error:", error);
                        }
                    });
                }

            });
        </script>

    @endsection

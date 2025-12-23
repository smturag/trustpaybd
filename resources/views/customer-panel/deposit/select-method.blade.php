<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('static/backend/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-size: 14px;
            color: #4c5258;
            letter-spacing: .5px;
            background: #f4f4f4;
            overflow-x: hidden;
            font-family: Roboto, sans-serif;
        }

        .logo-wrap {
            display: grid;
            padding-bottom: 1.5rem;
            gap: 1.25rem;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin: 50px auto;
        }

        .bank-img {
            margin-left: auto;
            margin-right: auto;
            height: 80%;
            width: 100%;
            -o-object-fit: cover;
            object-fit: cover;
            transition-property: all;
            transition-timing-function: cubic-bezier(.4, 0, .2, 1);
            transition-duration: 300ms;
        }

        .bank-img-div {
            border-radius: 0.375rem;
            display: flex;
            height: 4rem;
            justify-content: center;
            overflow: hidden;
            padding: 1px;
            border: 1px solid #ccc;
            align-items: center;
        }

        .btn-check:checked + .btn {
            border: 2px solid blue;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row row-cols-xl-2 mt-5">
        <div class="col mx-auto">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="p-4">
                        <div class="mb-4 text-center">
                            <h2 class="text-primary">Add Money</h2>
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
                                                

                        <form action="{{ route('deposit_payment', $customer_id) }}">

                            <div class="col-12 my-4 form-group">
                                <input type="number" id="deposit_amount" name="deposit_amount" placeholder="Enter Amount" class="form-control" required style="text-align: center; font-weight: bold; color: blue; font-size: 24px"/>
                            </div>

                            <div class="col-12 text-center btn-group">
                                <button type="button" class="btn btn-primary active">Mobile Banking</button>
                            </div>

                            <div class="logo-wrap">
                                @foreach($paymentMethods as $method)
                                    <div class="form-check">
                                        <input class="btn-check" type="radio" name="payment_method" value="{{ $method->name }}" id="{{ $method->name }}" required>
                                        <label class="btn border-1 bank-img-div" for="{{ $method->name }}">
                                            <img src="{{ asset($method->image) }}" class="bank-img"/>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-12 text-center btn-group">
                                <button type="submit" class="btn btn-primary">Proceed</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
<script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>

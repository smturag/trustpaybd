<div class="card-body m-2 p-2">
    <div class="item-content">

        <h2 style="font-size: 20px;margin-top: 30px;"><strong><u style="color:blue"> <i>REQUEST DATA
                    </i></u>:</strong> formdata (BODY)</h2>

        <div class="highlight"><pre
                style="color:#f8f8f2;background-color:#272822;-moz-tab-size:4;-o-tab-size:4;tab-size:4"><code
                    class="language-JSON hljs" data-lang="JSON">
        {
            "amount": "1100",
            "reference": "iuy87dFfJ",
            "currency": "BDT",
            "callback_url": "https://example.com",
            "cust_name": "arif",
            "cust_phone": "+855454454",
            "cust_address": "dhaka",
            "checkout_items": {
                "item1": {
                    "name": "Product1",
                    "size": "50kg",
                    "shape": "square"
                },
                "item2": {
                    "name": "Product2",
                    "size": "100kg",
                    "shape": "round"
                }
            },
            "note": "test"
        }


</code></pre>
        </div>
        <h4>
            Property Definition
        </h4>
        <div id="table-defination">
            <table class="table table-bordered">
                <thead>
                <tr class="">
                    <th>Property</th>
                    <th>Type</th>
                    <th>Example</th>
                    <th>Mandatory</th>
                    <th>Definition</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><span class="fw-bold">amount</span></td>
                    <td class="text-center">numeric</td>
                    <td class="text-center">100.00</td>
                    <td class="text-center">yes</td>
                    <td><strong>amount</strong> will be used as merchant payment
                    </td>
                </tr>
                <tr>
                    <td><span class="fw-bold">reference</span></td>
                    <td class="text-center">varchar(min:3, max: 20)</td>
                    <td class="text-center">inv-0215285852</td>
                    <td class="text-center">yes</td>
                    <td>Customer invoice/tracking number will be used as <strong>reference</strong></td>
                </tr>
                <tr>
                    <td><span class="fw-bold">currency</span></td>
                    <td class="text-center">varchar(4)</td>
                    <td class="text-center">BDT</td>
                    <td class="text-center">yes</td>
                    <td>always pass BDT as value</td>
                </tr>
                <tr>
                    <td><span class="fw-bold">callback_url</span></td>
                    <td class="text-center">text</td>
                    <td class="text-center">https://example.com/callback.php</td>
                    <td class="text-center">yes</td>
                    <td>A valid and working url where customer will be redirect to after successful/failed payment</td>
                </tr>

                <tr>
                    <td><span class="fw-bold">cust_name</span></td>
                    <td class="text-center">varchar(255)</td>
                    <td class="text-center">Ariful Islam</td>
                    <td class="text-center">yes</td>
                    <td>Customer name should pass here</td>
                </tr>

                <tr>
                    <td><span class="fw-bold">cust_phone</span></td>
                    <td class="text-center">varchar(15)</td>
                    <td class="text-center">+8801711XXYYZZ</td>
                    <td class="text-center">yes</td>
                    <td>Customer phone should pass here</td>
                </tr>
                <tr>
                    <td><span class="fw-bold">cust_address</span></td>
                    <td class="text-center">varchar(100)</td>
                    <td class="text-center">Dhaka, Bangladesh</td>
                    <td class="text-center">no</td>
                    <td>Customer address should pass here</td>
                </tr>

                <tr>
                    <td><span class="fw-bold">checkout_items</span></td>
                    <td class="text-center">array</td>
                    <td class="text-center">[ ]</td>
                    <td class="text-center">no</td>
                    <td>merchant may pass multiple products items or other types of data as array</td>
                </tr>

                <tr>
                    <td><span class="fw-bold">note</span></td>
                    <td class="text-center">varchar(100)</td>
                    <td class="text-center">some rext</td>
                    <td class="text-center">no</td>
                    <td></td>
                </tr>


                </tbody>
            </table>
        </div>

        <h2 style="font-size: 20px;margin-top: 30px;"><strong><u style="color:blue"> <i>Demo PHP Curl Request
                    </i></u>:</strong></h2>

        <div class="highlight"><pre
                style="color:#f8f8f2;background-color:#272822;-moz-tab-size:4;-o-tab-size:4;tab-size:4"><code
                    class="language-JSON hljs" data-lang="JSON">

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://payorio.com/api/v1/payment/create-payment',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "amount": "1100",
            "reference": "iuy87dFfJ",
            "currency": "BDT",
            "callback_url": "https://example.com",
            "cust_name": "arif",
            "cust_phone": "+855454454",
            "cust_address": "dhaka",
            "checkout_items": {
                "item1": {
                    "name": "Product1",
                    "size": "50kg",
                    "shape": "square"
                },
                "item2": {
                    "name": "Product2",
                    "size": "100kg",
                    "shape": "round"
                }
            },
            "note": "test"
        }',
        CURLOPT_HTTPHEADER => array(
            'X-Authorization: cFlTHJphTER2O3nAlV64T9fbjV85l9QuyWZaSKQeU7Z7oLBJpHQqs7PfwPrh9AJE',
            'X-Authorization-Secret: VxmTCiq76Hvbj1xByLw354ltvJISvnvefah9VEPjMlcj3LmVs7BcW1DDBeZZAHw3',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;



    </code></pre>
        </div>


        <h2 style="font-size: 20px;margin-top: 30px;"><strong><u style="color:blue"> <i>Response With Payment Success
                        Payment Url</i></u>:</strong></h2>
        <div class="highlight"><pre
                style="color:#f8f8f2;background-color:#272822;-moz-tab-size:4;-o-tab-size:4;tab-size:4"><code
                    class="language-JSON hljs" data-lang="JSON">
        {
            "status": "success",
            "message": "Payment created successfully",
            "data": {
                "payment_id": "abc123",
                "payment_url": "https://payment.example.com/abc123"
            }
        }
    </code></pre>

            <h2 style="font-size: 20px;margin-top: 30px;"><strong><u style="color:blue"> <i>Response with some
                            errors</i></u>:</strong></h2>
            <div class="highlight"><pre
                    style="color:#f8f8f2;background-color:#272822;-moz-tab-size:4;-o-tab-size:4;tab-size:4"><code
                        class="language-JSON hljs" data-lang="JSON">
         ===========unauthorized===========
        {
            "success": false,
            "code": 403,
            "message": "not authorized"
        }


        ===========Duplicate Reference Id===========
        {
            "success": false,
            "message": "Data validation error",
            "data": {
                "reference": [
                    "Duplicate reference-id iuy8767jyLKgJDKgFfJ"
                ]
            }
        }

        =============Fields Required==============
        {
            "success": false,
            "message": "Data validation error",
            "data": {
                "amount": [
                    "The amount field is required."
                ],
                "reference": [
                    "The reference field is required."
                ]
            }
        }

        ==============data type error==============
        {
            "success": false,
            "message": "Data validation error",
            "data": {
                "amount": [
                    "The amount field must be a number."
                ]
            }
        }


    </code></pre>
            </div>

        </div>
    </div>
</div>

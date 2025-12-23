<?php

namespace App\Service\Backend;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BkashService
{
    public function getActiveBkashMethod()
{
    $methods = PaymentMethod::where('type', 'api')
        ->where('status', 1)
        ->with('mfs_operator') // eager load
        ->get()
        ->groupBy(fn($method) => $method->mfs_operator->name ?? null) // group by operator name
        ->map(function ($group, $operatorName) {
            $method = $group->random(); // pick random PaymentMethod in this group

            return [
                'deposit_method' => $operatorName,
                'deposit_number' => $method->sim_id,
                'icon' => isset($method->mfs_operator->image) 
                    ? url('payments/' . $method->mfs_operator->image) 
                    : null,
                'type'=> 'P2C'
            ];
        })
        ->values(); // reset keys to 0,1,2...

    return $methods;
}

public function createPayment($data)
    {
        try {
            // Find payment method
            $paymentMethod = PaymentMethod::where('sim_id', $data->sim_id)
                ->where('type', 'api')
                ->first();

            if (!$paymentMethod) {
                return [
                    'success' => false,
                    'message' => 'Payment method not found',
                ];
            }

            // Get token
            $tokenResponse = $this->generateBkashToken($paymentMethod);
            if (!$tokenResponse['success']) {
                return [
                    'success' => false,
                    'message' => 'API token not generated',
                    'error'   => $tokenResponse['message'] ?? null,
                ];
            }

            $idToken = $tokenResponse['data']['id_token'] ?? null;
            if (!$idToken) {
                return [
                    'success' => false,
                    'message' => 'Invalid token response',
                ];
            }

            // Prepare payment request
            $createPaymentUrl = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/create';
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: ' . $idToken,
                'X-App-Key: ' . $paymentMethod->app_key,
            ];

            $payload = [
                'agreementID'             => $data->request_id,
                'mode'                    => '0011',
                'payerReference'          => '0000',
                'callbackURL'             => route('bkash_redirect'),
                'amount'                  => $data->amount,
                'currency'                => 'BDT',
                'intent'                  => 'sale',
                'merchantInvoiceNumber'   => $data->reference,
                'merchantAssociationInfo' => 'MI05MID54RF09123456789',
            ];

            // Call Bkash API
            $response = $this->sendCurlRequest($createPaymentUrl, $headers, $payload);
            if (!$response['success']) {
                return [
                    'success' => false,
                    'message' => 'Invalid response from bKash API',
                    'data'    => $responseData,
                ];
            }

            $responseData = $response['data'];
            if (!isset($responseData['bkashURL'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid response from bKash API',
                    'data'    => $responseData,
                ];
            }

            // Save session
            Session::put([
                'callbackURL'           => $data->callback_url,
                'merchantInvoiceNumber' => $data->reference,
                'amount'                => $data->amount,
                'merchant_id'           => $data->merchant_id,
            ]);

            // Update DB
            DB::table('payment_requests')
                ->where('request_id', $data->request_id)
                ->update([
                    'trxid'          => $responseData['paymentID'] ?? null,
                    'payment_method' => 'api_bkash',
                    'status'         => 0,
                    'updated_at'     => now(),
                ]);

            return [
                'success' => true,
                'data'    => $responseData['bkashURL'],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    public function generateBkashToken($paymentMethod)
    {
        try {
            $url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant';

            $data = [
                'app_key'    => $paymentMethod->app_key,
                'app_secret' => $paymentMethod->app_secret,
            ];

            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'username: ' . $paymentMethod->sim_id,
                'password: ' . $paymentMethod->password,
            ];

            $response = $this->sendCurlRequest($url, $headers, $data);
            if (!$response['success']) {
                return $response;
            }

            return [
                'success' => true,
                'data'    => $response['data'],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception in generateBkashToken: ' . $e->getMessage(),
            ];
        }
    }

    private function sendCurlRequest($url, $headers, $payload)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                return [
                    'success' => false,
                    'message' => 'cURL Error: ' . $error,
                ];
            }

            curl_close($ch);

            $decoded = json_decode($response, true);
            return [
                'success' => true,
                'data'    => $decoded,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception in sendCurlRequest: ' . $e->getMessage(),
            ];
        }
    }

        public function executePayment($paymentID, $idToken, $appKey)
        {
            $baseUrl = "https://tokenized.pay.bka.sh/v1.2.0-beta"; // move to config/services.php

            $response = Http::withHeaders([
                'Accept'        => 'application/json',
                'authorization' => $idToken,
                'x-app-key'     => $appKey,
            ])->post("{$baseUrl}/tokenized/checkout/execute", [
                'paymentID' => $paymentID,
            ]);

            return $response->json(); // no need to wrap again in response()->json()
        }



}
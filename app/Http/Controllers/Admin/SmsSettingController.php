<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SmsSetting::get();

        return view('admin.bulk_sms.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bulk_sms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        // $this->delete();

        $validator = Validator::make($request->all(), [
            'provider' => 'required',
            'common_link' => 'required',
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = new SmsSetting();

        $data->provider = $request->provider;
        $data->common_link = $request->common_link;
        $data->access_token = $request->access_token;

        if ($data->save()) {
            return Redirect::route('bulk_sms.index')->with('message', 'Data saved successfully.');
        }
        return Redirect::back()->with('alert', 'Failed to save data.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data = SmsSetting::where('id', $id)->first();

        return view('admin.bulk_sms.edit_view', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        // $data = new SmsSetting();
        $data = SmsSetting::findOrFail($id);
        $data->update($request->all());

        return Redirect::route('bulk_sms.index')->with('message', 'Data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = SmsSetting::find($id);
        $user->delete();
        return Redirect::route('bulk_sms.index')->with('message', 'Data deleted successfully.');
    }

    public function test_view(Request $request)
    {
        $data = SmsSetting::where('id', $request->id)->first();

        return view('admin.bulk_sms.test_view', compact('data'));
    }

    public function check_sms_connection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|min:13',
            'smsbody' => 'required|string',
            'provider_info' => 'required',
        ]);
        $provider = SmsSetting::where('provider', 'sms_city')->first();

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $post_data = [
            'contact' => [
                [
                    'number' => $request->mobile_number,
                    'message' => $request->smsbody,
                ],
                // Add other message data here
            ],
        ];

        if ($request->check_type == 1) {
            $response = Http::withHeaders([
                'Api-key' => $provider->access_token,
                'Content-Type' => 'application/json',
            ])->post('https://smscity.net/api/whatsapp/send', $post_data);

            if ($response->successful()) {
                // Successful response
                $responseData = $response->json();
                return Redirect::route('bulk_sms.test_view')->with('message', 'Successfully send this sms');

            } else {
                $errorMessage = $response->body();
                return Redirect::back()->with('alert', $errorMessage);
            }
        } elseif ($request->check_type == 0) {
            $response = Http::withHeaders([
                'Api-key' => $request->provider_info,
                'Content-Type' => 'application/json',
            ])->post('https://smscity.net/api/sms/send', $post_data);

            if ($response->successful()) {
                // Successful response
                $responseData = $response->json();
                return Redirect::route('bulk_sms.test_view')->with('message', $responseData);

                // Process the response data
            } else {
                $errorMessage = $response->body();
                return Redirect::back()->with('alert', $errorMessage);
            }
        }
    }
}

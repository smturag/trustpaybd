<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CryptoCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CryptoCurrencyController extends Controller
{
    public function index()
    {
        $data = DB::table('crypto_currencies')->get();

        return view('admin.crypto-currency.currency-list', compact('data'));
    }

    public function create_currency_form()
    {
        return view('admin.crypto-currency.create_currency_form');
    }

    public function edit_status(Request $request)
    {
        $data = CryptoCurrency::find($request->id);

        if ($request->status == '0') {
            $data->status = 1;
        } else {
            $data->status = 0;
        }
        $data->save();

        return $request;
    }

    public function save_currency_form(Request $request)
    {
        $check = CryptoCurrency::where('currency_name', $request->currency_name)
            ->where('network', $request->network)
            ->first();

        if (!$check) {
            $model = CryptoCurrency::create([
                'currency_name' => $request->currency_name,
                'network' => $request->network,
            ]);

            if ($model) {
                return redirect()->route('crypto.index')->with('message', 'Record deleted successfully.');
            } else {
                return Redirect::back()->with('alert', 'Failed to save data.');
            }
        }
        return Redirect::back()->with('alert', 'This data already exists.');
    }


    public function crypto_destroy(Request $request)
    {
        $record = CryptoCurrency::find($request->id);
        $record->delete();

        return redirect()->route('crypto.index')->with('message', 'Record deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyRateController extends Controller
{
    /**
     * Display listing of all currencies
     */
    public function index()
    {
        $currencies = CurrencyRate::orderBy('currency_code')->get();
        return view('admin.currency.index', compact('currencies'));
    }

    /**
     * Show form to create new currency
     */
    public function create()
    {
        return view('admin.currency.create');
    }

    /**
     * Store new currency
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|string|max:10|unique:currency_rates,currency_code',
            'currency_name' => 'required|string|max:50',
            'currency_symbol' => 'nullable|string|max:10',
            'exchange_rate_to_bdt' => 'required|numeric|min:0.000001',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        CurrencyRate::create([
            'currency_code' => strtoupper($request->currency_code),
            'currency_name' => $request->currency_name,
            'currency_symbol' => $request->currency_symbol,
            'exchange_rate_to_bdt' => $request->exchange_rate_to_bdt,
            'fee_percentage' => $request->fee_percentage,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.currency.index')
            ->with('success', 'Currency added successfully!');
    }

    /**
     * Show form to edit currency
     */
    public function edit($id)
    {
        $currency = CurrencyRate::findOrFail($id);
        return view('admin.currency.edit', compact('currency'));
    }

    /**
     * Update currency rate
     */
    public function update(Request $request, $id)
    {
        $currency = CurrencyRate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'currency_name' => 'required|string|max:50',
            'currency_symbol' => 'nullable|string|max:10',
            'exchange_rate_to_bdt' => 'required|numeric|min:0.000001',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Don't allow changing BDT rate
        if ($currency->currency_code === 'BDT' && $request->exchange_rate_to_bdt != 1) {
            return back()->with('error', 'BDT is the base currency. Exchange rate must be 1.00');
        }

        $currency->update([
            'currency_name' => $request->currency_name,
            'currency_symbol' => $request->currency_symbol,
            'exchange_rate_to_bdt' => $request->exchange_rate_to_bdt,
            'fee_percentage' => $request->fee_percentage,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.currency.index')
            ->with('success', 'Currency updated successfully!');
    }

    /**
     * Delete currency
     */
    public function destroy($id)
    {
        $currency = CurrencyRate::findOrFail($id);

        // Don't allow deleting BDT
        if ($currency->currency_code === 'BDT') {
            return back()->with('error', 'Cannot delete base currency (BDT)');
        }

        $currency->delete();

        return redirect()->route('admin.currency.index')
            ->with('success', 'Currency deleted successfully!');
    }
}

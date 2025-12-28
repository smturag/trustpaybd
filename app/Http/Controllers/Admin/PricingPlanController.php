<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PricingPlanController extends Controller
{
    /**
     * Display a listing of pricing plans
     */
    public function index()
    {
        $pricingPlans = PricingPlan::orderBy('display_order')->get();
        return view('admin.pricing.index', compact('pricingPlans'));
    }

    /**
     * Show the form for creating a new pricing plan
     */
    public function create()
    {
        return view('admin.pricing.create');
    }

    /**
     * Store a newly created pricing plan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'price_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $features = [];
        if ($request->has('features') && is_array($request->features)) {
            foreach ($request->features as $feature) {
                if (!empty($feature)) {
                    $features[] = $feature;
                }
            }
        }

        PricingPlan::create([
            'name' => $request->name,
            'price' => $request->price,
            'price_type' => $request->price_type ?? 'Per successful charge',
            'description' => $request->description,
            'features' => $features,
            'button_text' => $request->button_text ?? 'Get Started',
            'button_link' => $request->button_link,
            'is_featured' => $request->has('is_featured') ? true : false,
            'display_order' => $request->display_order ?? 0,
            'status' => $request->has('status') ? true : false,
        ]);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing plan created successfully');
    }

    /**
     * Show the form for editing the specified pricing plan
     */
    public function edit($id)
    {
        $pricingPlan = PricingPlan::findOrFail($id);
        return view('admin.pricing.edit', compact('pricingPlan'));
    }

    /**
     * Update the specified pricing plan
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'price_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pricingPlan = PricingPlan::findOrFail($id);

        $features = [];
        if ($request->has('features') && is_array($request->features)) {
            foreach ($request->features as $feature) {
                if (!empty($feature)) {
                    $features[] = $feature;
                }
            }
        }

        $pricingPlan->update([
            'name' => $request->name,
            'price' => $request->price,
            'price_type' => $request->price_type ?? 'Per successful charge',
            'description' => $request->description,
            'features' => $features,
            'button_text' => $request->button_text ?? 'Get Started',
            'button_link' => $request->button_link,
            'is_featured' => $request->has('is_featured') ? true : false,
            'display_order' => $request->display_order ?? 0,
            'status' => $request->has('status') ? true : false,
        ]);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing plan updated successfully');
    }

    /**
     * Remove the specified pricing plan
     */
    public function destroy($id)
    {
        $pricingPlan = PricingPlan::findOrFail($id);
        $pricingPlan->delete();

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing plan deleted successfully');
    }

    /**
     * Toggle the status of a pricing plan
     */
    public function toggleStatus($id)
    {
        $pricingPlan = PricingPlan::findOrFail($id);
        $pricingPlan->status = !$pricingPlan->status;
        $pricingPlan->save();

        return back()->with('success', 'Status updated successfully');
    }
}

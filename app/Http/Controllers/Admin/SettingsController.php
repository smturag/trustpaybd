<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SystemSetting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'AppName' => 'required|string|max:255',
            'AppLogo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
        ]);

        $brandName = $request->input('AppName');

        if ($request->hasFile('AppLogo')) {
            // Get the file from the request
            $file = $request->file('AppLogo');

            // Define the path to store the file
            $path = 'uploads/brand_images';

            // Store the file and get the path
            $filePath = $file->store($path, 'public');

            // Optionally, delete the old brand image if it exists
            $oldImage = app_config('AppLogo');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            // Update the settings with the new image path
            $settings = [
                'AppLogo' => $filePath,
                'wallet_payment_status' => $request->wallet_payment_status,
                'support_whatsapp_number' => $request->support_whatsapp_number,
            ];
        } else {
            // Only update the brand name if no new image is uploaded
            $settings = [
                'AppName' => $brandName,
                'wallet_payment_status' => $request->wallet_payment_status,
                'support_whatsapp_number' => $request->support_whatsapp_number,
            ];
        }

        // dd($settings);

        // Assuming you have a function to update your app configuration
        foreach ($settings as $key => $value) {
            // Replace this with your own logic to update the app config
            update_app_config($key, $value);
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Settings updated successfully!');
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenanceMode(Request $request)
    {
        $status = $request->input('status'); // 1 = ON, 0 = OFF

        if ($status == '1') {
            SystemSetting::enableMaintenanceMode();
            $message = 'Maintenance mode enabled. System is now under maintenance.';
        } else {
            SystemSetting::disableMaintenanceMode();
            $message = 'Maintenance mode disabled. System is now operational.';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $status,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get maintenance mode status
     */
    public function getMaintenanceStatus()
    {
        $status = SystemSetting::isMaintenanceMode();

        return response()->json([
            'success' => true,
            'maintenance_mode' => $status,
            'status' => $status ? '1' : '0',
        ]);
    }
}

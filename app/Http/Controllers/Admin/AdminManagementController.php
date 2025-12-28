<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index()
    {
        $admins = Admin::with('role')->latest()->paginate(20);
        return view('admin.admin-management.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.admin-management.create', compact('roles'));
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'admin_name' => 'required|string|max:255|unique:admins,admin_name',
            'password' => 'required|string|min:6|confirmed',
            'pincode' => 'required|string|min:4|max:6',
            'email' => 'nullable|email|max:255|unique:admins,email',
            'mobile' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $admin = Admin::create([
            'admin_name' => $request->admin_name,
            'username' => $request->admin_name,
            'password' => Hash::make($request->password),
            'pincode' => $request->pincode,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'type' => $request->type,
            'role_id' => $request->role_id,
            'status' => 1,
            'create_by' => auth()->guard('admin')->user()->admin_name ?? 'system',
            'db_status' => 'live',
        ]);

        // Assign role if provided
        if ($request->role_id) {
            \DB::table('model_has_roles')->insert([
                'role_id' => $request->role_id,
                'model_id' => $admin->id,
                'model_type' => Admin::class,
            ]);
        }

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified admin.
     */
    public function show(Admin $adminManagement)
    {
        $adminManagement->load('role');
        return view('admin.admin-management.show', compact('adminManagement'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(Admin $adminManagement)
    {
        $roles = Role::all();
        return view('admin.admin-management.edit', compact('adminManagement', 'roles'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, Admin $adminManagement)
    {
        $request->validate([
            'admin_name' => 'required|string|max:255|unique:admins,admin_name,' . $adminManagement->id,
            'password' => 'nullable|string|min:6|confirmed',
            'pincode' => 'nullable|string|min:4|max:6',
            'email' => 'nullable|email|max:255|unique:admins,email,' . $adminManagement->id,
            'mobile' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|in:0,1',
        ]);

        $data = [
            'admin_name' => $request->admin_name,
            'username' => $request->admin_name,
            'pincode' => $request->pincode,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'type' => $request->type,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $adminManagement->update($data);

        // Update role
        \DB::table('model_has_roles')
            ->where('model_id', $adminManagement->id)
            ->where('model_type', Admin::class)
            ->delete();

        if ($request->role_id) {
            \DB::table('model_has_roles')->insert([
                'role_id' => $request->role_id,
                'model_id' => $adminManagement->id,
                'model_type' => Admin::class,
            ]);
        }

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(Admin $adminManagement)
    {
        // Prevent self-deletion
        if ($adminManagement->id == auth()->guard('admin')->id()) {
            return redirect()->route('admin.admin-management.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Remove role assignments
        \DB::table('model_has_roles')
            ->where('model_id', $adminManagement->id)
            ->where('model_type', Admin::class)
            ->delete();

        $adminManagement->delete();

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin deleted successfully.');
    }

    /**
     * Toggle admin status
     */
    public function toggleStatus(Admin $adminManagement)
    {
        $adminManagement->update([
            'status' => $adminManagement->status == 1 ? 0 : 1
        ]);

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin status updated successfully.');
    }
}

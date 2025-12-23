<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MfsOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MFS_Controller extends Controller
{
    public function index()
    {
        $data = MfsOperator::all();

        return view('admin.mfs_operator.index', compact('data'));
    }

    public function create_mfs()
    {
        return view('admin.mfs_operator.create_mfs');
    }

    public function insert_mfs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mfs_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mfs_operators', 'name')->where(function ($query) use ($request) {
                    return $query->where('type', $request->mfs_type);
                }),
            ],
            'mfs_type' => 'required|string|max:20',
            'deposit_fee' => 'nullable|numeric|min:0',
            'deposit_commission' => 'nullable|numeric|min:0',
            'withdraw_fee' => 'nullable|numeric|min:0',
            'withdraw_commission' => 'nullable|numeric|min:0',
            'mfs_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mfs = new mfsOperator();
        $mfs->name = $request->mfs_name;
        $mfs->type = $request->mfs_type;
        $mfs->deposit_fee = $request->deposit_fee ?? 0;
        $mfs->deposit_commission = $request->deposit_commission ?? 0;
        $mfs->withdraw_fee = $request->withdraw_fee ?? 0;
        $mfs->withdraw_commission = $request->withdraw_commission ?? 0;
        $mfs->status = $request->has('mfs_status') ? 1 : 0;

        if ($request->hasFile('mfs_logo')) {
            $image = $request->file('mfs_logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $mfs->image = 'uploads/' . $imageName;
        } else {
            $mfs->image = ''; // Or set a default logo path if needed
        }

        if ($mfs->save()) {
            return redirect()->route('mfs.index')->with('message', 'MFS created successfully.');
        }

        return redirect()->back()->with('alert', 'Failed to save data.');
    }

    public function edit_mfs_view($id)
    {
        $mfs = mfsOperator::findOrFail($id);
        return view('admin.mfs_operator.edit_mfs', compact('mfs'));
    }

    // public function edit_mfs(Request $request)
    // {
    //     $mfs = mfsOperator::find($request->mfs_id);

    //     $validator = Validator::make($request->all(), [
    //         'mfs_name' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('mfs_operators', 'name')
    //                 ->where(function ($query) use ($request) {
    //                     return $query->where('type', $request->mfs_type);
    //                 })
    //                 ->ignore($mfs->id),
    //         ],
    //         'mfs_type' => 'required|string|max:20',
    //         'deposit_fee' => 'nullable|numeric|min:0',
    //         'deposit_commission' => 'nullable|numeric|min:0',
    //         'withdraw_fee' => 'nullable|numeric|min:0',
    //         'withdraw_commission' => 'nullable|numeric|min:0',
    //         'mfs_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $mfs->name = $request->mfs_name;
    //     $mfs->type = $request->mfs_type;
    //     $mfs->deposit_fee = $request->deposit_fee ?? 0;
    //     $mfs->deposit_commission = $request->deposit_commission ?? 0;
    //     $mfs->withdraw_fee = $request->withdraw_fee ?? 0;
    //     $mfs->withdraw_commission = $request->withdraw_commission ?? 0;
    //     $mfs->status = $request->has('mfs_status') ? 1 : 0;

    //     // Handle logo upload
    //     if ($request->hasFile('mfs_logo')) {
    //         $image = $request->file('mfs_logo');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $image->move(public_path('uploads'), $imageName);
    //         $mfs->image = 'uploads/' . $imageName;
    //     }

    //     if ($mfs->save()) {
    //         return redirect()->route('mfs.index')->with('message', 'MFS updated successfully.');
    //     }

    //     return redirect()->back()->with('alert', 'Failed to update data.');
    // }

    public function update_mfs(Request $request, $id)
    {
        $mfs = mfsOperator::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'mfs_name' => ['required', 'string', 'max:255', Rule::unique('mfs_operators', 'name')->where(fn($query) => $query->where('type', $request->mfs_type))->ignore($mfs->id)],
            'mfs_type' => 'required|string|max:20',
            'deposit_fee' => 'nullable|numeric|min:0',
            'deposit_commission' => 'nullable|numeric|min:0',
            'withdraw_fee' => 'nullable|numeric|min:0',
            'withdraw_commission' => 'nullable|numeric|min:0',
            'mfs_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Update fields
        $mfs->name = $request->mfs_name;
        $mfs->type = $request->mfs_type;
        $mfs->deposit_fee = $request->deposit_fee ?? 0;
        $mfs->deposit_commission = $request->deposit_commission ?? 0;
        $mfs->withdraw_fee = $request->withdraw_fee ?? 0;
        $mfs->withdraw_commission = $request->withdraw_commission ?? 0;
        $mfs->status = $request->has('mfs_status') ? 1 : 0;

        // ✅ Handle logo upload (optional)
        if ($request->hasFile('mfs_logo')) {
            $image = $request->file('mfs_logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/mfs_logos'), $imageName);

            // Delete old image if exists
            if ($mfs->image && file_exists(public_path($mfs->image))) {
                @unlink(public_path($mfs->image));
            }

            $mfs->image = 'uploads/mfs_logos/' . $imageName;
        }

        $mfs->save();

        return redirect()->route('mfs.index')->with('message', 'MFS updated successfully.');
    }

    public function mfs_destroy(Request $request)
    {
        // Retrieve the record you want to delete
        $record = mfsOperator::find($request->id);

        // Delete the record
        $record->delete();

        // Perform any additional actions, such as displaying a success message

        return redirect()->route('mfs.index')->with('message', 'Record deleted successfully.');
    }

    public function status_update(Request $request)
    {
        $data = mfsOperator::find($request->id);
        if ($request->status == '0') {
            $data->status = 1;
        } else {
            $data->status = 0;
        }
        $data->save();

        return $request;
    }
}

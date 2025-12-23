@extends('admin.layouts.admin_app')
@section('title', 'Edit MFS')

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{ session('message') }}
        </div>
    @endif

    @if (Session::has('alert'))
        <div class="alert alert-danger">{{ Session::get('alert') }}</div>
    @endif

    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Edit MFS</h5>
                    <hr>

                    <form action="{{ route('mfs.update_mfs', $mfs->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf

                        {{-- MFS Name --}}
                        <div class="col-md-12">
                            <label for="mfs_name" class="form-label">MFS Name</label>
                            <input type="text" name="mfs_name" value="{{ old('mfs_name', $mfs->name) }}" class="form-control" placeholder="MFS Name" readonly>
                            @error('mfs_name')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- MFS Type --}}
                        <div class="col-md-12">
                            <label for="mfs_type" class="form-label">MFS Type</label>
                            <select name="mfs_type" id="mfs_type" class="form-control">
                                <option value="">Select MFS Type</option>
                                <option value="P2A" {{ old('mfs_type', $mfs->type) == 'P2A' ? 'selected' : '' }}>P2A</option>
                                <option value="P2C" {{ old('mfs_type', $mfs->type) == 'P2C' ? 'selected' : '' }}>P2C</option>
                                <option value="P2P" {{ old('mfs_type', $mfs->type) == 'P2P' ? 'selected' : '' }}>P2P</option>
                            </select>
                            @error('mfs_type')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Deposit Fee --}}
                        <div class="col-md-6">
                            <label for="deposit_fee" class="form-label">Deposit Fee (%)</label>
                            <input type="number" step="0.01" name="deposit_fee" value="{{ old('deposit_fee', $mfs->deposit_fee) }}" class="form-control" placeholder="Deposit Fee">
                            @error('deposit_fee')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Deposit Commission --}}
                        <div class="col-md-6">
                            <label for="deposit_commission" class="form-label">Deposit Commission (%)</label>
                            <input type="number" step="0.01" name="deposit_commission" value="{{ old('deposit_commission', $mfs->deposit_commission) }}" class="form-control" placeholder="Deposit Commission">
                            @error('deposit_commission')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Withdraw Fee --}}
                        <div class="col-md-6">
                            <label for="withdraw_fee" class="form-label">Withdraw Fee (%)</label>
                            <input type="number" step="0.01" name="withdraw_fee" value="{{ old('withdraw_fee', $mfs->withdraw_fee) }}" class="form-control" placeholder="Withdraw Fee">
                            @error('withdraw_fee')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Withdraw Commission --}}
                        <div class="col-md-6">
                            <label for="withdraw_commission" class="form-label">Withdraw Commission (%)</label>
                            <input type="number" step="0.01" name="withdraw_commission" value="{{ old('withdraw_commission', $mfs->withdraw_commission) }}" class="form-control" placeholder="Withdraw Commission">
                            @error('withdraw_commission')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-12">
                            <label for="switch-button" class="form-label">MFS Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" id="switch-button" type="checkbox" name="mfs_status"
                                    {{ old('mfs_status', $mfs->status) ? 'checked' : '' }}>
                                <label class="form-check-label" id="status-label" for="switch-button">
                                    {{ old('mfs_status', $mfs->status) ? 'on' : 'off' }}
                                </label>
                            </div>
                        </div>

                        {{-- Logo --}}
                        <div class="col-md-12">
                            <label class="form-label">MFS Logo</label>
                            <div class="mb-2">
                                @if ($mfs->image)
                                    <img src="{{ asset($mfs->image) }}" alt="MFS Logo" width="80" class="rounded border">
                                @endif
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" name="mfs_logo" class="form-control" id="inputGroupFile02">
                                <label class="input-group-text" for="inputGroupFile02">Upload New Logo</label>
                                @error('mfs_logo')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                                <a href="{{ route('mfs.index') }}" class="btn btn-secondary px-4">Cancel</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#switch-button').on('change', function() {
            $('#status-label').text($(this).is(':checked') ? 'on' : 'off');
        });
    });
</script>
@endpush

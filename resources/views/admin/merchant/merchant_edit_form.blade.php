@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@section('content')

    <div class="row justify-content-center">
        <div class="col-xl-6">

            <div class="card">
                <div class="card-body p-4">

                    <h5 class="mb-4">{{ translate('merchant') }} Edit</h5>
                    <form class="row g-3" action="{{ route('merchant_update', ['id' => $user->id]) }}" method="POST">
                        @csrf
                        <div class="col-md-12">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" name="fullname"
                                class="form-control @error('fullname') is-invalid @enderror" id="fullname"
                                value="{{ $user->fullname }}">
                            @error('fullname')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" value="{{ $user->email }}">
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="mobile" class="form-label">Phone</label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                                id="mobile" value="{{ $user->mobile }}">
                            @error('mobile')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" id="password">
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="withdraw_status" id="withdraw_status"
                                    <?php if ($user->withdraw_status == 1) {
                                        echo 'checked';
                                    } ?>>
                                <label class="form-check-label" for="withdraw_status">
                                    {{ translate('Withdraw') }}
                                </label>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="deposit_status" id="deposit_status"
                                    <?php if ($user->deposit_status == 1) {
                                        echo 'checked';
                                    } ?>>
                                <label class="form-check-label" for="deposit_status">
                                    {{ translate('Deposit') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="v1_p2c" id="v1_p2c"
                                    <?php if ($user->v1_p2c == 1) {
                                        echo 'checked';
                                    } ?>>
                                <label class="form-check-label" for="v1_p2c">
                                    {{ translate('V1 P2C') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="v1_p2a" id="v1_p2a"
                                    <?php if ($user->v1_p2a == 1) {
                                        echo 'checked';
                                    } ?>>
                                <label class="form-check-label" for="v1_p2a">
                                    {{ translate('V1 P2A') }}
                                </label>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="v1_p2p" id="v1_p2p"
                                    <?php if ($user->v1_p2p == 1) {
                                        echo 'checked';
                                    } ?>>
                                <label class="form-check-label" for="v1_p2p">
                                    {{ translate('V1 P2P') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="v1_manual_gateway" id="v1_manual_gateway"
                                    <?php if ($user->v1_manual_gateway == 1) {
                                        echo 'checked';
                                    } ?>>
                                <label class="form-check-label" for="v1_manual_gateway">
                                    {{ translate('V1 Manual Gateway') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="v1_direct_gateway" id="v1_direct_gateway"
                                    <?php if ($user->v1_direct_gateway == 1) {
                                        echo 'checked';
                                    } ?>>
                                <label class="form-check-label" for="v1_direct_gateway">
                                    {{ translate('V1 Direct Gateway') }}
                                </label>
                            </div>
                        </div>




                        <div class="col-md-12">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="status" value="1"
                                    <?php if ($user->status == 1) {
                                        echo "checked='checked'";
                                    } ?>>
                                <span class="custom-control-label">{{ translate('active') }}
                                </span>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@extends('customer-panel.customer_app')
@section('title', 'Dashboard')

@section('customer_content')
    <div class="container rounded mt-5 mb-5">
        <div class="row">
            <div class="col-md-4 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold">{{$profile_data->fullname}}</span><span class="text-black-50">{{$profile_data->email}}</span><span> </span></div>
            </div>
            <div class="col-md-8 border-right">


                <img src="{{ $profile_data->profile_pic ? url('storage/app/public/'. $profile_data->profile_pic) : asset('path/to/default/profile_picture.jpg') }}" alt="Profile Picture">

                <form action="{{ route('customerProfileUpdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Profile Settings</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" placeholder="full name" value="{{$profile_data->fullname}}" name="name"></div>
                            @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                            <div class="col-md-6"><label class="labels">User Name</label><input type="text" class="form-control" value="{{$profile_data->username}}" name="username" readonly></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" placeholder="enter phone number" value="{{$profile_data->mobile}}" name="mobile"></div>
                            @error('mobile')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                            <div class="col-md-12"><label class="labels">Email ID</label><input type="text" class="form-control" placeholder="enter email id" value="{{$profile_data->email}}" name="email"></div>
                            @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="labels">Update Profile Pic</label>
                                    <input type="file" name="profile_pic">
                                </div>

                                <div class="col-md-6">
                                    <label class="labels">Update Password</label>
                                    <button type="button" class="btn btn-secondary btn-md btn-block" data-toggle="modal" data-target="#changePassModal">Change Password</button>
                                </div>
                            </div>

                            <!--<div class="mt-4">-->
                            <!--  <button type="button" class="btn btn-secondary btn-md btn-block" data-toggle="modal" data-target="#changePassModal" >Change Password</button>-->
                            <!--</div>-->
                            <!--<div class="mt-4">-->
                            <!--    <label class="labels">Change Profile Pic</label></label>-->
                            <!-- <input type="file" name="profile_pic">-->
                            <!--</div>-->
                        </div>
                        <div class="mt-5 text-center">
                            <button class="btn btn-primary profile-button" type="submit">Save Profile</button>
                        </div>
                    </div>

                </form>
            </div>
            <!--<div class="col-md-4">-->
            <!--    <div class="p-3 py-5">-->
            <!--        <div class="d-flex justify-content-between align-items-center experience"><span>Edit Experience</span><span class="border px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Experience</span></div><br>-->
            <!--        <div class="col-md-12"><label class="labels">Experience in Designing</label><input type="text" class="form-control" placeholder="experience" value=""></div> <br>-->
            <!--        <div class="col-md-12"><label class="labels">Additional Details</label><input type="text" class="form-control" placeholder="additional details" value=""></div>-->
            <!--    </div>-->
            <!--</div>-->

            <!-- Change password -->
            <div class="modal fade" id="changePassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update or change Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form method="post" action="{{ route('customerChangePassword') }}">

                                @csrf

                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input id="current_password" type="password" class="form-control" name="current_password" required>
                                    @error('current_password')
                                    <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input id="new_password" type="password" class="form-control" name="new_password" required>
                                    @error('new_password')
                                    <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirm Password</label>
                                    <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
                                    @error('new_password_confirmation')
                                    <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
    </div>

    <style>
        body {
            background: #fffff
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #BA68C8
        }

        .profile-button {
            background: rgb(99, 39, 120);
            box-shadow: none;
            border: none
        }

        .profile-button:hover {
            background: #682773
        }

        .profile-button:focus {
            background: #682773;
            box-shadow: none
        }

        .profile-button:active {
            background: #682773;
            box-shadow: none
        }

        .back:hover {
            color: #682773;
            cursor: pointer
        }

        .labels {
            font-size: 11px
        }

        .add-experience:hover {
            background: #BA68C8;
            color: #fff;
            cursor: pointer;
            border: solid 1px #BA68C8
        }

        .container {
            background-color: #f0f0f0;
        }
    </style>

@endsection


@push('js')
    <script type="text/javascript">

    </script>
@endpush

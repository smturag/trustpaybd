@extends('admin.layouts.admin_app')
@section('title', 'Mc User')





@section('content')

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-none d-sm-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">MobCash User List</h6>
                <a class="ms-auto btn btn-sm btn-primary" href="{{ route('mcuserAdd') }}">
                    <i class="bx bx-plus mr-1"></i> New MobCash User
                </a>
            </div>
            <hr/>
            <hr />

            @if (session('message'))
                <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-white">Success Alerts</h6>
                            <div class="text-white">{{ session('message') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="#" class="mb-5 justify-content-center" role="form" method="post" id="search-form"
                        accept-charset="utf-8">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="rows">{{ translate('show') }}</label>
                                    <select class="form-control" name="rows" id="rows">
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="150">150</option>
                                        <option value="200">200</option>
                                        <option value="400">400</option>
                                        <option value="500">500</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date1">Userid</label>
                                    <input type="text" class="form-control" name="userid" id='userid'>
                                </div>
                            </div>





                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="" for="">&nbsp;</label><br />
                                    <button type="submit" class="btn btn-danger btn-block"><span
                                            class="fa fa-search"></span> {{ translate('Search') }} </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="tableContentWrap">


                        <table id="req_table" class="table table-hover mb-0 text-center align-middle table-bordered">
                            <thead>
                                <tr>

                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col">Userid</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">WorkCode</th>
                                    <th scope="col">Currency</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            @foreach ($userlist as $urow)
                            <tbody>
                               
                                    <td>{{ $urow->id }}</td>
                                    <td>{{ $urow->userid }}</td>
                                    <td>*****</td>
                                    <td>{{ $urow->workcode }}</td>
                                    <td>{{ $urow->currency }}</td>
                                    <td>{{ $urow->location }}</td>
                                    <td>{{ $urow->balance }}</td>
                                    <td>
                                        @if ($urow->status == 1)
                                            <span class='badge badge-pill bg-success'>Active</span>
                                        @elseif($urow->status == 0)
                                            <span class='badge badge-pill bg-info text-white'>Banned</span>
                                        @endif
                                    </td>
                                    <td>

                                        @if ($urow->status == 1)
                                        <a href="{{ route('mcuser_active',$urow->id) }}" class="btn btn-sm btn-success"> Banned </a>
                                        @else
                                        <a href="{{ route('mcuser_active',$urow->id) }}" class="btn btn-sm btn-danger"> Active </a>
                                        @endif
                                    </td>
                              
                            </tbody>
                            @endforeach


                    </div>




                </div>
            </div>
        </div>
    </div>


@endsection

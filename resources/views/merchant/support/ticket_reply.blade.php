@extends('merchant.mrc_app')
@section('title', 'Dashboard')
@section('mrc_content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{ session('message') }}
        </div>
        @elseif (session()->has('alert'))
            <div class="alert alert-warning" id="alert_warning">
                {{ session('alert') }}
            </div>
        @endif
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">{{ translate('reply_ticket') }}</h5>
                        @if ($ticket_object->status == 1)
                            <button class="btn btn-warning pull-right"> Opened</button>
                        @elseif($ticket_object->status == 2)
                            <button type="button" class="btn btn-success pull-right"> Answered </button>
                        @elseif($ticket_object->status == 3)
                            <button type="button" class="btn btn-info pull-right"> Customer Reply </button>
                        @elseif($ticket_object->status == 9)
                            <button type="button" class="btn btn-danger pull-right"> Closed </button>
                        @endif
                        <a href="{{ route('merchant.ticketClose', $ticket_object->ticket) }}"
                            class="btn btn-danger pull-right make-close-support" style="height: 35px;">Click To Make
                            Close</a>
                        <div class="panel-heading"> #{{ $ticket_object->ticket }} - {{ $ticket_object->subject }}
                        </div>
                        <form class="row g-3" method="POST"
                            action="{{ route('merchant.ticketReplyStore', $ticket_object->ticket) }}" accept-charset="UTF-8">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-12">

                                    {{-- @dd($ticket_data) --}}

                                    @foreach ($ticket_data as $data)
                                        <div class="panel-body">
                                            <fieldset class="col-md-12">
                                                @if ($data->type == 1)
                                                    <legend><span style="color: #0e76a8">{{ auth('merchant')->user()->fullname }}</span>
                                                        ,
                                                        <small>{{ \Carbon\Carbon::parse($data->updated_at)->format('F dS, Y - h:i A') }}</small>
                                                    </legend>
                                                @else
                                                    <legend><span
                                                            style="color: #0e76a8">{{ app_config('AppTitle') }}</span> ,
                                                        <small>{{ \Carbon\Carbon::parse($data->updated_at)->format('F dS, Y - h:i A') }}</small>
                                                    </legend>
                                                @endif
                                                <div class="panel panel-danger">
                                                    <div class="panel-body">
                                                        <p>{!! $data->comment !!}</p>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h5>Admin</h5>
                                                        <p>{!! $data->comment_reply !!}</p>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="clearfix"></div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                            <div class="form-group {{ $errors->has('comment') ? ' has-error' : '' }}">

                                <label class="col-md-12 bold">Reply: </label>

                                <div class="col-md-12">

                                    <textarea class="form-control" name="comment" rows="10"></textarea>

                                    @if ($errors->has('comment'))
                                        <span class="help-block">

                                            <strong>{{ $errors->first('comment') }}</strong>

                                        </span>
                                    @endif

                                </div>

                            </div>



                            <div class="box-footer">

                                <div class="row">



                                    <div class="col-md-12">

                                        <button type="submit"
                                            class="btn btn-info btn-block pull-right">{{ translate('submit_now') }}</button>

                                    </div>

                                </div>

                            </div>

                        </form>


                    </div>

                </div>

            </div>

        </div>



    @endsection

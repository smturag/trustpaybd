
@extends('member.layout.member_app')

@section('member_content')

								
							
@if ($errors->any())
	
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
	
	
@endif

@if(session()->has('message'))
 <div class="alert alert-success" id="alert_success">
   {{session('message')}}
 </div>
@endif

@if(session()->has('alert'))
 <div class="alert alert-warning" id="alert_warning">
   {{session('alert')}}
 </div>
@endif        


   <div class="row">
	<div class="col-xl-6">
				
					<div class="card">
                        <div class="card-body p-4">   

 <h5 class="mb-4">{{ translate('create_ticket') }}</h5>						

 <form method="POST" action="{{route('ticketStore')}}" accept-charset="UTF-8" class="row g-3">
                                    {{csrf_field()}}

                           
                                                       
                                                      <div class="form-group {{ $errors->has('subject') ? ' has-error' : '' }}">
                                            <label class="col-md-12 bold">Subject Name: <span class="required">
                                        * </span>
                                            </label>
                                            <div class="col-md-12">
                                                <input type="text"  value="{{ old('subject') }}"  class="form-control" required name="subject" placeholder="Title Name" >
                                                @if ($errors->has('subject'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('subject') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                                        
										<div class="form-group {{ $errors->has('detail') ? ' has-error' : '' }}">
                                            <label class="col-md-12 bold">Message: </label>
                                            <div class="col-md-12">
                                                <textarea class="form-control" name="detail" rows="10"></textarea>
                                                @if ($errors->has('detail'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('detail') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                                <div class="box-footer">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success btn-lg btn-block pull-right">{{ translate('submit_now') }}</button>
                                                    </div>
                                                    </div>
                                                </div>
                                                
												</form>
                                                </div>
                                                </div>
                                                </div>
                                                </div>


                                                       

@endsection
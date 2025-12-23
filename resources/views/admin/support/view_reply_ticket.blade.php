@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')
@push('css')
@endpush
@section('content')



    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.submitSolutionTicket') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <h5 class="card-title ">Ticket: <mark>{{ $data['ticket_head']->ticket }}</mark></h5>
                        <h5 class="card-title">Name: {{ $data['ticket_user']->fullname }}</h5>
                    </div>
                    <div class="col-lg-6 col-md-6 ">
                        <h5 class="card-title">Created at:
                            <mark>{{ \Carbon\Carbon::parse($data['ticket_head']->created_at)->format('F dS, Y - h:i A') }}</mark>
                        </h5>
                        <h6 class="card-title">User Type:
                            {{ $data['ticket_head']->customer_type == 0 ? 'Merchant' : 'User' }}
                        </h6>
                    </div>
                </div>
                <hr>
                <div>
                    <div>
                        <h5 class="card-title">Ticket Subject: <span >{{ $data['ticket_head']->subject }}</span>
                        </h5>
                        <hr>
                        <h6> <b>Conversation </b></h6>
                        <hr>
                    </div>
                    @foreach ($data['ticket'] as $item)                   

                    <div class="card-title">
                        <div > <h5 style="display: inline; color: chocolate"> {{ $data['ticket_user']->fullname }}   </h5> <p style="display: inline;">{{ \Carbon\Carbon::parse($item->created_at)->format('F dS, Y - h:i A') }}</p></div>  <span class="">
                            <p style="font-size: 20px">{{ $item->comment }}</p>
                        </span>
                       @if($item->comment_reply !== null)
                        <div>                           
                            <h5 style="color:rgb(38, 24, 231)">  Admin Reply </h5>
                            <p style="font-size: 20px">{{ $item->comment_reply }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <hr>
                <div class="form-group">
                    <label class="col-md-12 bold">Message: </label>
                    <div class="col-md-12">
                        <textarea class="form-control" name="detail" rows="5" required></textarea>
                        <input type="text" name='ticket' value="{{ $data['ticket_head']->ticket }}" hidden>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-danger">Submit Solution</button>               
                    <button type="button" onclick="closeTicket('{{ $data['ticket_head']->ticket }}')" class="btn btn-info">Close Ticket</button>
                </div>
              
            </form>
        </div>
    </div>


@endsection

@push('js')
    <script>
        function closeTicket(ticket) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            const formdata = {
                ticket_name:ticket
            }
            $.ajax({
                url: "{{ route('admin.closeTicket') }}", // Replace 'submit-form' with the route to handle form submission in Laravel
                type: "POST",
                data: formdata,
                headers: {
                'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
            },
                success: function(response) {
                    // Handle the response from the server (if needed)
                    if(response.success == true){
                        var confirmation = confirm('This support is closed submitted');
                        window.location.href = '{{ route("admin.support_list") }}';
                     }
                },
                error: function(error) {
                    // Handle errors (if any)
                    console.error(error);
                }
            });
        };
    </script>
@endpush

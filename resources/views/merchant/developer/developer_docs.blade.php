@extends(auth()->check() ? 'merchant.mrc_app' : 'welcome')

@if(auth()->check())
    @section('title', 'Dashboard')
    @section('mrc_content')
        @else
            @section('customer')
                @endif

                @push('css')

                    <style>
                        .token-text {
                            background-color: #e4e4e4;
                            padding: 13px 10px;
                        }

                        .note-block {
                            border: 1px solid #ddd;
                            padding: 15px;
                            width: 100%;
                        }

                        .note-block ul {

                        }

                        .note-block ul li {

                        }

                        .note-block-list {

                        }

                        .code_block {
                            border: 1px solid #ddd;
                        }

                    </style>

                @endpush


                <div class="col {{ auth()->guest() ? ' mt-30 pt-186 ' : '' }}">

                    <h3 class="mb-0 text-uppercase">iPay BD Payment Gateway</h3>

                    <p>For accessing to iPay BD payment gateway you will need to generate <strong>public key</strong> and <strong>secret key</strong> from merchant developer settings</p>
                    <p>After that send those two keys as header , mentioned in example code below</p>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-danger" role="tablist">

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab"
                                       aria-selected="false"
                                       tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Create Payment</div>
                                        </div>
                                    </a>
                                </li>

                            </ul>


                            @include('merchant.developer.payment_create')


                        </div>
                    </div>
                </div>

            @endsection

            @push('js')
                <script type="text/javascript">
                    var elems = document.getElementsByClassName('confirmation');
                    var confirmIt = function (e) {
                        if (!confirm('Are you sure?')) e.preventDefault();
                    };
                    for (var i = 0, l = elems.length; i < l; i++) {
                        elems[i].addEventListener('click', confirmIt, false);
                    }
                </script>
            @endpush

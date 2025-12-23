@extends('merchant.mrc_app')
@section('title', 'Dashboard')

@section('mrc_content')
    @push('css')

        <style>
            .token-section {
                display: flex;
                flex-wrap: wrap;
                gap: 24px;
                margin-bottom: 24px;
            }
            .token-card {
                flex: 1 1 260px;
                min-width: 260px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(80, 120, 200, 0.07);
                padding: 22px 20px 18px 20px;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 0;
            }
            .token-label {
                font-weight: 700;
                font-size: 1.08rem;
                margin-bottom: 10px;
                color: #2d3a4a;
            }
            .token-box {
                background: #f4f6fb;
                border: 1px solid #e0e3ea;
                border-radius: 8px;
                padding: 12px 16px;
                font-family: 'Fira Mono', 'Consolas', monospace;
                font-size: 1.08rem;
                display: flex;
                align-items: center;
                gap: 10px;
                width: 100%;
                margin-bottom: 0;
                word-break: break-all;
            }
            .token-action-btn {
                border: none;
                background: none;
                cursor: pointer;
                color: #4b8df8;
                font-size: 1.2rem;
                padding: 0 6px;
                transition: color 0.2s;
            }
            .token-action-btn:focus {
                outline: none;
                color: #1a5ad7;
            }
            .token-action-btn:hover {
                color: #1a5ad7;
            }
            @media (max-width: 600px) {
                .token-section {
                    flex-direction: column;
                    gap: 12px;
                }
                .token-card {
                    padding: 16px 10px 12px 10px;
                }
                .token-label {
                    font-size: 1rem;
                }
                .token-box {
                    font-size: 0.98rem;
                }
            }
        </style>

    @endpush

    <div class="card radius-10">
        <div class="card-body">
            <div class="token-section">
                <div class="token-card">
                    <div class="token-label">Public Key</div>
                    <div class="token-box">
                        <span id="publicKeyText" style="letter-spacing:1px;">••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••</span>
                        <button class="token-action-btn" id="togglePublicKeyBtn" title="Show/Hide Public Key">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="token-action-btn" id="copyPublicKeyBtn" title="Copy Public Key">
                            <i class="bx bx-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="token-card">
                    <div class="token-label">Secret Key</div>
                    <div class="token-box">
                        <span id="secretKeyText" style="letter-spacing:1px;">••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••</span>
                        <button class="token-action-btn" id="toggleSecretKeyBtn" title="Show/Hide Secret Key">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="token-action-btn" id="copySecretKeyBtn" title="Copy Secret Key">
                            <i class="bx bx-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="repeater-remove-btn mt-2" style="text-align:center;">
                @if($merchantToken)
                    <a class="btn btn-outline-primary confirmation" href="{{ route('merchant.developer.api-key-generate') }}">
                        <i class="bx bx-refresh"></i> Regenerate Now
                    </a>
                @else
                    <a href="{{ route('merchant.developer.api-key-generate') }}" class="btn btn-primary confirmation">
                        <i class="bx bx-plus"></i> Generate Now
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- IP Whitelist Card -->
    <div class="card radius-10 mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div>
                    <h5 class="mb-0">IP Whitelist</h5>
                    <p class="text-secondary mb-0 mt-1">Restrict API access to specific IP addresses</p>
                </div>
                <div class="ms-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIpModal">
                        <i class="bx bx-plus-circle me-1"></i> Add IP Address
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Added On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($ipWhitelist) > 0)
                            @foreach($ipWhitelist as $ip)
                                <tr>
                                    <td><code>{{ $ip->ip_address }}</code></td>
                                    <td>{{ $ip->description ?? 'N/A' }}</td>
                                    <td>
                                        @if($ip->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Disabled</span>
                                        @endif
                                    </td>
                                    <td>{{ $ip->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('merchant.developer.ip-whitelist.toggle', $ip->id) }}" class="btn btn-sm btn-outline-primary confirmation-toggle">
                                                @if($ip->is_active)
                                                    <i class="bx bx-pause"></i> Disable
                                                @else
                                                    <i class="bx bx-play"></i> Enable
                                                @endif
                                            </a>
                                            <a href="{{ route('merchant.developer.ip-whitelist.delete', $ip->id) }}" class="btn btn-sm btn-outline-danger confirmation-delete">
                                                <i class="bx bx-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-globe fs-1 d-block mb-2"></i>
                                        <p>No IP addresses in whitelist. API can be accessed from any IP address.</p>
                                        <p class="small">Add IP addresses to restrict API access to specific locations.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="alert alert-info mt-3">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="bx bx-info-circle fs-3"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading">IP Whitelist Information</h6>
                        <p class="mb-0">When IP whitelist is empty, API can be accessed from any IP address. Once you add IP addresses to the whitelist, API access will be restricted to only those IPs.</p>
                        <p class="mb-0 mt-2">You can add individual IP addresses (IPv4 or IPv6) or CIDR ranges (e.g., 192.168.1.0/24).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Developer Documentation Card -->
    <div class="card radius-10 mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div>
                    <h5 class="mb-0">Developer Documentation</h5>
                    <p class="text-secondary mb-0 mt-1">See API integration and usage docs for developers.</p>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('develop_docs') }}" class="btn btn-outline-primary" target="_blank">
                        <i class="bx bx-book-open me-1"></i> View Developer Docs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add IP Modal -->
    <div class="modal fade" id="addIpModal" tabindex="-1" aria-labelledby="addIpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('merchant.developer.ip-whitelist.add') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addIpModalLabel">Add IP Address to Whitelist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="e.g., 192.168.1.1 or 2001:db8::1 or 192.168.1.0/24" required>
                            <div class="form-text">Enter IPv4, IPv6, or CIDR notation</div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="e.g., Office Network">
                            <div class="form-text">Optional description to help identify this IP address</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add to Whitelist</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .button-link {
            display: inline-block;
            color: #fff; /* Set text color to white */
            text-decoration: none; /* Remove underline */
        }

        .button-link:hover {
            text-decoration: none; /* Remove underline on hover */
            color: #fff;
        }

        /* IP Whitelist styles */
        .table th {
            font-weight: 600;
        }

        .table code {
            background-color: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }
    </style>

@endsection


@push('js')
    <script type="text/javascript">
        // API Key regeneration confirmation
        var elems = document.getElementsByClassName('confirmation');
        var confirmIt = function (e) {
            if (!confirm('Are you sure you want to regenerate your API keys? This will invalidate your current keys.')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }

        // IP whitelist toggle confirmation
        var toggleElems = document.getElementsByClassName('confirmation-toggle');
        var confirmToggle = function (e) {
            if (!confirm('Are you sure you want to change the status of this IP address?')) e.preventDefault();
        };
        for (var i = 0, l = toggleElems.length; i < l; i++) {
            toggleElems[i].addEventListener('click', confirmToggle, false);
        }

        // IP whitelist delete confirmation
        var deleteElems = document.getElementsByClassName('confirmation-delete');
        var confirmDelete = function (e) {
            if (!confirm('Are you sure you want to remove this IP address from the whitelist?')) e.preventDefault();
        };
        for (var i = 0, l = deleteElems.length; i < l; i++) {
            deleteElems[i].addEventListener('click', confirmDelete, false);
        }

        // Public Key show/hide
        let publicVisible = false;
        const publicKeyText = document.getElementById('publicKeyText');
        const togglePublicKeyBtn = document.getElementById('togglePublicKeyBtn');
        const realPublic = @json($merchantToken->api_key ?? '');
        togglePublicKeyBtn.onclick = function() {
            publicVisible = !publicVisible;
            if (publicVisible) {
                publicKeyText.innerText = realPublic;
                this.innerHTML = '<i class="bx bx-hide"></i>';
            } else {
                publicKeyText.innerText = '••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••';
                this.innerHTML = '<i class="bx bx-show"></i>';
            }
        };

        // Copy Public Key
        document.getElementById('copyPublicKeyBtn').onclick = function() {
            const text = publicVisible ? publicKeyText.innerText : realPublic;
            navigator.clipboard.writeText(text);
            this.innerHTML = '<i class="bx bx-check"></i>';
            setTimeout(() => { this.innerHTML = '<i class="bx bx-copy"></i>'; }, 1200);
        };

        // Show/Hide Secret Key
        let secretVisible = false;
        const secretKeyText = document.getElementById('secretKeyText');
        const toggleSecretKeyBtn = document.getElementById('toggleSecretKeyBtn');
        const realSecret = @json($merchantToken->secret_key ?? '');
        toggleSecretKeyBtn.onclick = function() {
            secretVisible = !secretVisible;
            if (secretVisible) {
                secretKeyText.innerText = realSecret;
                this.innerHTML = '<i class="bx bx-hide"></i>';
            } else {
                secretKeyText.innerText = '••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••';
                this.innerHTML = '<i class="bx bx-show"></i>';
            }
        };

        // Copy Secret Key
        document.getElementById('copySecretKeyBtn').onclick = function() {
            const text = secretVisible ? secretKeyText.innerText : realSecret;
            navigator.clipboard.writeText(text);
            this.innerHTML = '<i class="bx bx-check"></i>';
            setTimeout(() => { this.innerHTML = '<i class="bx bx-copy"></i>'; }, 1200);
        };

        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            });
        }, 5000);
    </script>
@endpush

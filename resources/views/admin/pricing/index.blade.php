@extends('admin.layouts.admin_app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Pricing Plans Management</h1>
            <p class="text-muted mb-0">Manage pricing plans displayed on the homepage</p>
        </div>
        <a href="{{ route('admin.pricing.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Plan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Plan Name</th>
                            <th>Price</th>
                            <th>Features</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pricingPlans as $plan)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $plan->display_order }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $plan->name }}</strong>
                                    @if($plan->is_featured)
                                        <span class="badge bg-warning text-dark ms-1">Featured</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ $plan->price_type }}</small>
                                </td>
                                <td>
                                    <strong class="fs-5">{{ $plan->price }}</strong>
                                </td>
                                <td>
                                    @if($plan->features && is_array($plan->features))
                                        <ul class="list-unstyled mb-0">
                                            @foreach(array_slice($plan->features, 0, 3) as $feature)
                                                <li><small><i class="fas fa-check text-success"></i> {{ $feature }}</small></li>
                                            @endforeach
                                            @if(count($plan->features) > 3)
                                                <li><small class="text-muted">+{{ count($plan->features) - 3 }} more</small></li>
                                            @endif
                                        </ul>
                                    @else
                                        <span class="text-muted">No features</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->is_featured)
                                        <span class="badge bg-warning text-dark">Yes</span>
                                    @else
                                        <span class="badge bg-light text-dark">No</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.pricing.toggle-status', $plan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($plan->status)
                                            <button type="submit" class="badge bg-success border-0">Active</button>
                                        @else
                                            <button type="submit" class="badge bg-secondary border-0">Inactive</button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.pricing.edit', $plan->id) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.pricing.destroy', $plan->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this pricing plan?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No pricing plans found. Create one to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

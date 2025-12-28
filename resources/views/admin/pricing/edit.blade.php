@extends('admin.layouts.admin_app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.pricing.index') }}">Pricing Plans</a></li>
                <li class="breadcrumb-item active">Edit Plan</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">Edit Pricing Plan: {{ $pricingPlan->name }}</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.pricing.update', $pricingPlan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Plan Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $pricingPlan->name) }}"
                                   placeholder="e.g., Starter, Professional, Enterprise"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">
                                    Price <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $pricingPlan->price) }}"
                                       placeholder="e.g., 1%, $99, Custom"
                                       required>
                                <small class="text-muted">Can be percentage, amount, or text like "Custom"</small>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="price_type" class="form-label">
                                    Price Type
                                </label>
                                <input type="text" 
                                       class="form-control @error('price_type') is-invalid @enderror" 
                                       id="price_type" 
                                       name="price_type" 
                                       value="{{ old('price_type', $pricingPlan->price_type) }}"
                                       placeholder="e.g., Per successful charge, Per month">
                                @error('price_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="2"
                                      placeholder="e.g., For small businesses">{{ old('description', $pricingPlan->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Features
                            </label>
                            <div id="features-container">
                                @php
                                    $features = old('features', $pricingPlan->features ?? []);
                                @endphp
                                @if(is_array($features) && count($features) > 0)
                                    @foreach($features as $feature)
                                        <div class="input-group mb-2 feature-item">
                                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="features[]" 
                                                   value="{{ $feature }}"
                                                   placeholder="Enter feature">
                                            <button type="button" class="btn btn-outline-danger remove-feature">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 feature-item">
                                        <span class="input-group-text"><i class="fas fa-check"></i></span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="features[]" 
                                               placeholder="Enter feature">
                                        <button type="button" class="btn btn-outline-danger remove-feature">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-feature">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="button_text" class="form-label">
                                    Button Text
                                </label>
                                <input type="text" 
                                       class="form-control @error('button_text') is-invalid @enderror" 
                                       id="button_text" 
                                       name="button_text" 
                                       value="{{ old('button_text', $pricingPlan->button_text) }}"
                                       placeholder="e.g., Get Started, Contact Sales">
                                @error('button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="button_link" class="form-label">
                                    Button Link
                                </label>
                                <input type="text" 
                                       class="form-control @error('button_link') is-invalid @enderror" 
                                       id="button_link" 
                                       name="button_link" 
                                       value="{{ old('button_link', $pricingPlan->button_link) }}"
                                       placeholder="e.g., /register, #contact">
                                @error('button_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="display_order" class="form-label">
                                Display Order
                            </label>
                            <input type="number" 
                                   class="form-control @error('display_order') is-invalid @enderror" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="{{ old('display_order', $pricingPlan->display_order) }}"
                                   min="0">
                            <small class="text-muted">Lower numbers appear first</small>
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_featured" 
                                       name="is_featured"
                                       {{ old('is_featured', $pricingPlan->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Plan (highlights with special styling)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="status" 
                                       name="status"
                                       {{ old('status', $pricingPlan->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active (visible on website)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Pricing Plan
                            </button>
                            <a href="{{ route('admin.pricing.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tips</h5>
                    <ul class="small text-muted">
                        <li>Use clear, descriptive plan names</li>
                        <li>Price can be percentage (1%), amount ($99), or text (Custom)</li>
                        <li>Add features that highlight plan benefits</li>
                        <li>Featured plans get special visual styling</li>
                        <li>Display order determines the position on the page</li>
                        <li>Inactive plans won't be shown on the website</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add feature functionality
    document.getElementById('add-feature').addEventListener('click', function() {
        const container = document.getElementById('features-container');
        const newFeature = `
            <div class="input-group mb-2 feature-item">
                <span class="input-group-text"><i class="fas fa-check"></i></span>
                <input type="text" class="form-control" name="features[]" placeholder="Enter feature">
                <button type="button" class="btn btn-outline-danger remove-feature">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newFeature);
    });

    // Remove feature functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-feature') || e.target.closest('.remove-feature')) {
            const featureItem = e.target.closest('.feature-item');
            if (document.querySelectorAll('.feature-item').length > 1) {
                featureItem.remove();
            }
        }
    });
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="display-6 fw-bold">
                    <i class="fas fa-list me-2"></i>My Orders
                </h2>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Order
                </a>
            </div>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>{{ $order->order_code }}
                        </h6>
                        <span class="badge status-{{ str_replace('_', '-', $order->status) }}">
                            {{ $order->status_display }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Service Details</h6>
                                <p class="mb-1"><strong>Service:</strong> {{ $order->service->name }}</p>
                                <p class="mb-1"><strong>Pickup:</strong> {{ ucfirst($order->pickup_method) }}</p>
                                @if($order->weight)
                                    <p class="mb-1"><strong>Weight:</strong> {{ $order->weight }} kg</p>
                                @endif
                                @if($order->view_proof)
                                    <p class="mb-1">
                                        <strong>Weighing Proof:</strong><br>
                                        <a href="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" target="_blank">
                                            <img src="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" alt="Weighing Proof" class="img-thumbnail mt-1" width="150">
                                        </a>
                                    </p>
                                @endif
                                @if($order->price)
                                    <p class="mb-1"><strong>Price:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Order Info</h6>
                                <p class="mb-1"><strong>Customer:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                @if($order->estimated_completion)
                                    <p class="mb-1"><strong>Est. Completion:</strong> 
                                        {{ $order->estimated_completion->format('M d, Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        @if($order->notes)
                            <div class="mt-3">
                                <h6 class="fw-bold">Notes</h6>
                                <p class="text-muted">{{ $order->notes }}</p>
                            </div>
                        @endif

                        @if($order->status === 'waiting_for_payment' && $order->user_id)
                            <div class="mt-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Please upload your view proof to continue processing your order.
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                            @if($order->status === 'waiting_for_payment' && $order->user_id)
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $order->id }}">
                                    <i class="fas fa-upload me-1"></i>Upload Payment
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Payment Modal -->
            @if($order->status === 'waiting_for_payment' && $order->user_id)
            <div class="modal fade" id="uploadModal{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload View Proof</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label">View Proof Image</label>
                                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" 
                                           accept="image/*" required>
                                    <div class="form-text">Please upload a clear image of your receipt or transfer proof.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Upload View Proof</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-tshirt fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No orders yet</h4>
                        <p class="text-muted mb-4">Create your first order to get started with our laundry services!</p>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Create Your First Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_code)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="display-6 fw-bold">
                    <i class="fas fa-receipt me-2"></i>Order Details
                </h2>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Order Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Order Details</h6>
                            <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
                            <p><strong>Service:</strong> {{ $order->service->name }}</p>
                            <p><strong>Pickup Method:</strong> {{ ucfirst($order->pickup_method) }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge status-{{ str_replace('_', '-', $order->status) }} fs-6">
                                    {{ $order->status_display }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Customer Information</h6>
                            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                            <p><strong>Address:</strong> {{ $order->customer_address }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                            @if($order->estimated_completion)
                                <p><strong>Est. Completion:</strong> 
                                    {{ $order->estimated_completion->format('M d, Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mt-4">
                            <h6 class="fw-bold">Special Instructions</h6>
                            <p class="text-muted">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->weight && $order->price)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Weight:</strong> {{ $order->weight }} kg</p>
                            <p><strong>Price per kg:</strong> Rp {{ number_format($order->service->price_per_kg, 0, ',', '.') }}</p>
                            <p><strong>Total Price:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Payment Status:</strong> 
                                @if($order->payment_verified)
                                    <span class="badge bg-success">Verified</span>
                                @elseif($order->payment_proof)
                                    <span class="badge bg-warning">Pending Verification</span>
                                @else
                                    <span class="badge bg-danger">Not Paid</span>
                                @endif
                            </p>
                            @if($order->payment_proof)
                                <p><strong>Payment Proof:</strong> 
                                    <a href="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" 
                                       target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Proof
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Hasil Timbangan (admin uploaded) -->
            @if($order->view_proof || $order->weight)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-weight-hanging me-2"></i>Hasil Timbangan
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->weight)
                        <p><strong>Berat (ditetapkan admin):</strong> {{ $order->weight }} kg</p>
                    @endif

                    @if($order->view_proof)
    <p class="mb-2 fw-semibold text-dark">Bukti Timbangan:</p>
    

    <a href="{{ asset('storage/scale_proofs/' . $order->view_proof) }}"
       target="_blank"
       class="btn btn-outline-success rounded-pill px-4 py-2">
        <i class="fas fa-eye me-2"></i>Lihat Bukti Timbangan
    </a>

    <p class="text-muted small mt-2">
        Klik tombol untuk membuka foto bukti timbangan.
    </p>

                    @else
                        <div class="alert alert-secondary mb-0 rounded-3">
                            Bukti timbangan belum tersedia.
                        </div>
                    @endif

                </div>
            </div>
            @endif

            <!-- Upload Payment Proof -->
            @if($order->status === 'waiting_for_payment' && $order->user_id)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>Upload Payment Proof
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Please upload a clear image of your payment receipt or transfer proof to continue processing your order.
                    </div>
                    
                    <form action="{{ route('orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_proof" class="form-label">Payment Proof Image</label>
                            <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" 
                                   id="payment_proof" name="payment_proof" accept="image/*" required>
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Accepted formats: JPG, PNG, GIF. Maximum size: 2MB</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload Proof
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Order Status Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-timeline me-2"></i>Order Progress
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $statuses = [
                                'waiting_for_pickup' => ['Waiting for Pickup', 'fas fa-clock'],
                                'picked_and_weighed' => ['Picked & Weighed', 'fas fa-weight-hanging'],
                                'waiting_for_payment' => ['Waiting for Payment', 'fas fa-credit-card'],
                                'waiting_for_admin_verification' => ['Waiting for Verification', 'fas fa-user-check'],
                                'processed' => ['Processed', 'fas fa-cog'],
                                'completed' => ['Completed', 'fas fa-check-circle']
                            ];
                        @endphp
                        
                        @foreach($statuses as $status => $info)
                            <div class="timeline-item d-flex align-items-center mb-3">
                                <div class="timeline-marker me-3">
                                    @if(array_search($status, array_keys($statuses)) <= array_search($order->status, array_keys($statuses)))
                                        <i class="{{ $info[1] }} text-primary"></i>
                                    @else
                                        <i class="{{ $info[1] }} text-muted"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0 {{ array_search($status, array_keys($statuses)) <= array_search($order->status, array_keys($statuses)) ? 'text-primary' : 'text-muted' }}">
                                        {{ $info[0] }}
                                    </h6>
                                    @if($status === $order->status)
                                        <small class="text-muted">Current status</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Service Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tshirt me-2"></i>Service Details
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">{{ $order->service->name }}</h6>
                    <p class="text-muted">{{ $order->service->description }}</p>
                    <p><strong>Price:</strong> Rp {{ number_format($order->service->price_per_kg, 0, ',', '.') }}/kg</p>
                    <p><strong>Estimated Time:</strong> {{ $order->service->estimated_days }} day(s)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
}

.timeline-marker {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-purple);
    border-radius: 50%;
    border: 2px solid var(--primary-purple);
}

.timeline-content {
    flex: 1;
}
</style>
@endsection

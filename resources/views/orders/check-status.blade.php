@extends('layouts.app')

@section('title', 'Check Order Status')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-search me-2"></i>Check Order Status
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('orders.check') }}">
                        <div class="mb-4">
                            <label for="order_code" class="form-label">Enter Your Order Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg @error('order_code') is-invalid @enderror" 
                                       id="order_code" name="order_code" value="{{ request('order_code') }}" 
                                       placeholder="e.g., ATN-20251018-ABC" required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-2"></i>Check Status
                                </button>
                            </div>
                            @error('order_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter your order code to check the current status of your laundry order.
                            </div>
                        </div>
                    </form>

                    @if(isset($order))
                        @if($order)
                            <div class="mt-4">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-check-circle me-2"></i>Order Found
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold">Order Details</h6>
                                                <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
                                                <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                                                <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                                                <p><strong>Service:</strong> {{ $order->service->name }}</p>
                                                <p><strong>Order Type:</strong> 
                                                    <span class="badge {{ $order->order_type === 'login' ? 'bg-primary' : 'bg-secondary' }}">
                                                        {{ ucfirst($order->order_type) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold">Status Information</h6>
                                                <p><strong>Current Status:</strong> 
                                                    <span class="badge fs-6" style="color: #000; background-color: #e0e0e0;">
                                                        {{ $order->status_display }}
                                                    </span>

                                                </p>
                                                @if($order->weight)
                                                    <p><strong>Weight:</strong> {{ $order->weight }} kg</p>
                                                @endif
                                                @if($order->price)
                                                    <p><strong>Price:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                                                @endif
                                                @if($order->estimated_completion)
                                                    <p><strong>Estimated Completion:</strong> 
                                                        {{ $order->estimated_completion->format('M d, Y H:i') }}
                                                    </p>
                                                @endif
                                                <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
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
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Please upload your payment proof to continue processing your order.
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-4">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No order found with the code "{{ request('order_code') }}". Please check your order code and try again.
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

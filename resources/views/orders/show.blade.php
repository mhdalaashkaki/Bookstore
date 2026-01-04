@extends('layouts.app')

@section('title', __('messages.order_details') . ' - ' . __('messages.store_name'))

@section('content')
<style>
    .order-detail-container {
        max-width: 900px;
        margin: 2rem auto;
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .order-header {
        border-bottom: 2px solid #eee;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .order-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .order-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-box {
        padding: 1rem;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .info-label {
        font-size: 0.9rem;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: bold;
        color: #1e293b;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-processing {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-shipped {
        background: #cfe2ff;
        color: #084298;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
    }

    .items-table th {
        background: #667eea;
        color: white;
        padding: 1rem;
        text-align: right;
        font-weight: bold;
    }

    .items-table td {
        padding: 1rem;
        border-bottom: 1px solid #eee;
        text-align: right;
    }

    .items-table tr:hover {
        background: #f9f9f9;
    }

    .items-table .product-name {
        font-weight: bold;
        color: #1a2f5a;
    }

    .items-table .price {
        color: #667eea;
        font-weight: bold;
    }

    .total-section {
        text-align: left;
        padding: 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
        margin: 2rem 0;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: 1.1rem;
    }

    .total-final {
        border-top: 2px solid #ddd;
        padding-top: 1rem;
        font-size: 1.3rem;
        font-weight: bold;
        color: #667eea;
    }

    .notes-section {
        padding: 1.5rem;
        background: #f0f7ff;
        border-right: 3px solid #667eea;
        border-radius: 8px;
        margin: 2rem 0;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        font-weight: bold;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        background: #764ba2;
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #2c3e50;
    }

    .btn-secondary:hover {
        background: #ddd;
    }

    .timeline {
        margin: 2rem 0;
        padding: 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 1rem;
    }

    .timeline-marker {
        width: 40px;
        height: 40px;
        background: #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-left: 1rem;
        flex-shrink: 0;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-content h4 {
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .timeline-content p {
        color: #1e293b;
        font-size: 0.9rem;
    }
</style>

<div class="container">
    <div class="order-detail-container">
        <div class="order-header">
            <div class="order-title">📦 {{ __('messages.order_details') }} #{{ $order->id }}</div>

            <div class="order-info" style="margin-top: 1rem;">
                <div class="info-box">
                    <div class="info-label">{{ __('messages.buyer_name') }}</div>
                    <div class="info-value">{{ $order->user->name }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">{{ __('messages.email') }}</div>
                    <div class="info-value" style="font-size: 0.95rem;">{{ $order->user->email }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">{{ __('messages.order_date') }}</div>
                    <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">{{ __('messages.status') }}</div>
                    <div style="margin-top: 0.3rem;">
                        <span class="status-badge status-{{ $order->status }}">
                            {{ trans_status($order->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <h3 style="color: #1e293b; margin: 2rem 0 1rem 0;">📚 {{ __('messages.items') }}:</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ __('messages.product_name') }}</th>
                    <th>{{ __('messages.price') }}</th>
                    <th>{{ __('messages.quantity') }}</th>
                    <th>{{ __('messages.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td class="product-name">
                            {{ $item->product?->name ?? __('messages.product_unavailable') }}
                            @if($item->product?->is_rejected)
                                <span style="color:#dc3545; font-weight:bold; margin-right:6px;">({{ __('messages.rejected') }})</span>
                            @elseif(!$item->product)
                                <span style="color:#dc3545; font-weight:bold; margin-right:6px;">({{ __('messages.deleted') }})</span>
                            @endif
                        </td>
                        <td class="price">${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="price">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>{{ __('messages.total_price') }}:</span>
                <span class="total-final">${{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>

        @if($order->notes)
            <!-- Order Notes -->
            <div class="notes-section">
                <h4 style="color: #667eea; margin-bottom: 0.5rem;">📝 {{ __('messages.notes') }}:</h4>
                <p>{{ $order->notes }}</p>
            </div>
        @endif

        <!-- Order Timeline -->
        <div class="timeline">
            <h3 style="color: #333; margin-bottom: 1rem;">📅 {{ __('messages.order_stages') }}:</h3>

            <div class="timeline-item">
                <div class="timeline-marker">1</div>
                <div class="timeline-content">
                    <h4>{{ __('messages.order_received') }}</h4>
                    <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            @if($order->status !== 'pending')
                <div class="timeline-item">
                    <div class="timeline-marker">2</div>
                    <div class="timeline-content">
                        <h4>{{ __('messages.processing') }}</h4>
                        <p>{{ __('messages.preparing_shipment') }}</p>
                    </div>
                </div>
            @endif

            @if($order->status === 'shipped' || $order->status === 'completed')
                <div class="timeline-item">
                    <div class="timeline-marker">3</div>
                    <div class="timeline-content">
                        <h4>{{ __('messages.shipped') }}</h4>
                        <p>{{ __('messages.on_the_way') }}</p>
                    </div>
                </div>
            @endif

            @if($order->status === 'completed')
                <div class="timeline-item">
                    <div class="timeline-marker">4</div>
                    <div class="timeline-content">
                        <h4>{{ __('messages.delivered') }}</h4>
                        <p>{{ __('messages.thank_you') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Buttons -->
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('messages.continue_shopping') }}</a>
            <a href="{{ route('orders.user') }}" class="btn btn-secondary">{{ __('messages.back_to_orders') }}</a>
        </div>
    </div>
</div>
@endsection

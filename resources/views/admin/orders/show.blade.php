@extends('layouts.app')

@section('title', __('messages.order_details'))

@section('content')
<div class="container">
    <h1 style="margin: 2rem 0 1rem;">{{ __('messages.order_details') }} #{{ $order->id }}</h1>

    <div style="background:#fff; padding:1rem; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.05); margin-bottom:1rem; color: #2c3e50;">
        <p><strong>User:</strong> {{ optional($order->user)->name }}</p>
        <p><strong>{{ __('messages.order_status') }}:</strong> {{ trans_status($order->status) }}</p>
        <p><strong>{{ __('messages.order_total') }}:</strong> {{ number_format($order->total_price, 2) }}</p>
        <p><strong>Notes:</strong> {{ $order->notes ?: 'None' }}</p>
        <p><strong>{{ __('messages.created_at') }}:</strong> {{ $order->created_at?->format('Y-m-d H:i') }}</p>
    </div>

    <h3 style="margin: 1rem 0;">{{ __('messages.order_details') }}</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; background:#fff; direction: {{ is_rtl() ? 'rtl' : 'ltr' }};">
            <thead>
                <tr style="background:#f5f5f5; text-align:start; color:#1e293b;">
                    <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">Product</th>
                    <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.quantity') }}</th>
                    <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.price') }}</th>
                    <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr style="color: #2c3e50;">
                        <td style="padding:10px; border-bottom:1px solid #f0f0f0;">
                            {{ $item->product?->name ?? 'Product Not Available' }}
                            @if($item->product?->is_rejected)
                                <span style="color:#dc3545; font-weight:bold; margin-right:6px;">(Rejected)</span>
                            @elseif(!$item->product)
                                <span style="color:#dc3545; font-weight:bold; margin-right:6px;">(Deleted)</span>
                            @endif
                        </td>
                        <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $item->quantity }}</td>
                        <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ number_format($item->price, 2) }}</td>
                        <td style="padding:10px; border-bottom:1px solid #f0f0f0;">
                            @if($item->product && !$item->product->is_rejected)
                                <form action="{{ route('admin.products.reject', $item->product) }}" method="POST" onsubmit="return confirm('Confirm rejection?');" style="margin:0; display:inline;">
                                    @csrf
                                    <button type="submit" style="background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">{{ __('messages.delete') }}</button>
                                </form>
                            @elseif($item->product?->is_rejected)
                                <form action="{{ route('admin.products.restore', $item->product) }}" method="POST" onsubmit="return confirm('Confirm restore?');" style="margin:0; display:inline;">
                                    @csrf
                                    <button type="submit" style="background:#198754; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">Restore</button>
                                </form>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <form action="{{ route('admin.orders.status', $order) }}" method="POST" style="margin-top:1.5rem; display:flex; gap:.5rem; align-items:center;">
        @csrf
        @method('PUT')
        <label style="color: #2c3e50; font-weight: bold;">Update Status:</label>
        <select name="status" required style="padding:.6rem; border:1px solid #ddd; border-radius:6px;">
            @foreach(['pending','processing','shipped','completed'] as $status)
                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ trans_status($status) }}</option>
            @endforeach
        </select>
        <button type="submit" style="padding: .6rem 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#fff; border:none; border-radius:6px; cursor:pointer;">{{ __('messages.save') }}</button>
    </form>

    @if($order->status === 'completed')
        <form action="{{ route('admin.orders.delete', $order) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm') }} delete?');" style="margin-top:1rem;">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding: .6rem 1rem; background:#dc3545; color:#fff; border:none; border-radius:6px; cursor:pointer;">{{ __('messages.delete') }}</button>
        </form>
    @endif
</div>
@endsection

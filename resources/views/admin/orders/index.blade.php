@extends('layouts.app')

@section('title', __('messages.manage_orders'))

@section('content')
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin: 2rem 0 1rem; flex-wrap:wrap; gap:0.5rem;">
        <h1 style="margin:0;">{{ __('messages.orders') }}</h1>
        <a href="{{ route('admin.orders.completed') }}" style="background:#667eea; color:#fff; padding:8px 14px; border-radius:6px; text-decoration:none;">{{ __('messages.completed_orders') }}</a>
    </div>

    @if($orders->count())
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; background:#fff; direction: {{ is_rtl() ? 'rtl' : 'ltr' }};">
                <thead>
                    <tr style="background:#f5f5f5; text-align:start; color:#1e293b;">
                        <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">#</th>
                        <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.user') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.total') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.status') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.created_at') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee; color:#1e293b;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="color: #2c3e50;">
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $order->id }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ optional($order->user)->name }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">${{ number_format($order->total_price, 2) }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">
                                @if($order->is_cancelled)
                                    <span style="background:#f8d7da;color:#721c24;padding:4px 10px;border-radius:12px;font-size:0.85rem;">{{ __('messages.cancelled') }}</span>
                                @else
                                    <span style="background:#eef2ff;color:#4338ca;padding:4px 10px;border-radius:12px;font-size:0.85rem;">{{ trans_status($order->status) }}</span>
                                @endif
                            </td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">
                                <a href="{{ route('admin.orders.show', $order) }}" style="color:#667eea; text-decoration:none;">{{ __('messages.show') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">{{ $orders->links() }}</div>
    @else
        <p>{{ __('messages.no_orders') }}</p>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', __('messages.completed_orders'))

@section('content')
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin: 2rem 0 1rem; flex-wrap:wrap; gap:0.5rem;">
        <h1 style="margin:0;">{{ __('messages.completed_orders') }}</h1>
        <a href="{{ route('admin.orders') }}" style="background:#f0f0f0; color:#2c3e50; padding:8px 14px; border-radius:6px; text-decoration:none; font-weight: bold;">{{ __('messages.back') }}</a>
    </div>

    @if($orders->count())
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; background:#fff;">
                <thead>
                    <tr style="background:#f5f5f5; text-align:right;">
                        <th style="padding:10px; border-bottom:1px solid #eee;">#</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">User</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.order_total') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.updated_at') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">Status</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="color: #2c3e50;">
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $order->id }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ optional($order->user)->name }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">${{ number_format($order->total_price, 2) }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $order->updated_at?->format('Y-m-d H:i') }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">
                                @if($order->trashed())
                                    <span style="background:#f8d7da;color:#721c24;padding:4px 10px;border-radius:12px;font-size:0.85rem;">Deleted</span>
                                @else
                                    <span style="background:#d4edda;color:#155724;padding:4px 10px;border-radius:12px;font-size:0.85rem;">{{ __('messages.completed') }}</span>
                                @endif
                            </td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0; display:flex; gap:8px; flex-wrap:wrap;">
                                @unless($order->trashed())
                                    <a href="{{ route('admin.orders.show', $order) }}" style="color:#667eea; text-decoration:none;">{{ __('messages.view_details') }}</a>
                                    <form action="{{ route('admin.orders.delete', $order) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm') }} delete?');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#dc3545; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">{{ __('messages.delete') }}</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.orders.forceDelete', $order->id) }}" method="POST" onsubmit="return confirm('Permanent delete?');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#b02a37; color:#fff; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">Force Delete</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">{{ $orders->links() }}</div>
    @else
        <p>{{ __('messages.no_data') }}</p>
    @endif
</div>
@endsection


@extends('layouts.app')

@section('title', __('messages.admin_panel') . ' - ' . __('messages.store_name'))

@section('content')
<style>
    .admin-container {
        max-width: 1200px;
        margin: 2rem auto;
    }

    .admin-header {
        color: white;
        padding: 2rem;
        background: linear-gradient(135deg, #0b1e3b 0%, #1a2f5a 100%);
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .admin-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .admin-header p {
        opacity: 0.9;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-top: 4px solid #667eea;
    }

    .stat-card.orange {
        border-top-color: #ffc107;
    }

    .stat-card.green {
        border-top-color: #28a745;
    }

    .stat-card.red {
        border-top-color: #dc3545;
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
    }

    .admin-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #667eea;
        padding-bottom: 0.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
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
        color: #333;
    }

    .btn-secondary:hover {
        background: #ddd;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th {
        background: #f9f9f9;
        padding: 1rem;
        text-align: start;
        font-weight: bold;
        color: #1e293b;
        border-bottom: 2px solid #ddd;
    }

    .orders-table td {
        padding: 1rem;
        border-bottom: 1px solid #ddd;
        text-align: start;
    }

    .orders-table tr:hover {
        background: #f9f9f9;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
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

    .menu-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .menu-link {
        padding: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .menu-link:hover {
        transform: translateY(-4px);
    }

    .menu-link-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #666;
    }
</style>

<div class="admin-container">
    <div class="admin-header">
        <h1>👨‍💼 {{ __('messages.admin_dashboard') }}</h1>
        <p>{{ __('messages.manage_bookstore') }} - {{ __('messages.welcome') }} {{ auth()->user()->name }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-label">{{ __('messages.total_products') }}</div>
            <div class="stat-value">{{ $productsCount }}</div>
            <p style="color: #999; font-size: 0.9rem; margin-top: 0.3rem;">{{ $activeProducts }} {{ __('messages.active') }}</p>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon">📦</div>
            <div class="stat-label">{{ __('messages.total_orders') }}</div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <p style="color: #999; font-size: 0.9rem; margin-top: 0.3rem;">{{ $pendingOrders }} {{ __('messages.pending') }}</p>
        </div>

        <div class="stat-card green">
            <div class="stat-icon">👥</div>
            <div class="stat-label">{{ __('messages.total_users_count') }}</div>
            <div class="stat-value">{{ $usersCount }}</div>
            <p style="color: #999; font-size: 0.9rem; margin-top: 0.3rem;">{{ __('messages.active_customers') }}</p>
        </div>

        <div class="stat-card red">
            <div class="stat-icon">⚙️</div>
            <div class="stat-label">{{ __('messages.processing_orders') }}</div>
            <div class="stat-value">{{ $pendingOrders }}</div>
            <p style="color: #999; font-size: 0.9rem; margin-top: 0.3rem;">{{ __('messages.needs_attention') }}</p>
        </div>
    </div>

    <!-- Management Menu -->
    <div class="admin-section">
        <div class="section-title">🎯 {{ __('messages.admin_menu') }}</div>
        <div class="menu-links">
            <a href="{{ route('admin.products') }}" class="menu-link">
                <div class="menu-link-icon">📚</div>
                <div>{{ __('messages.manage_products') }}</div>
            </a>
            <a href="{{ route('admin.orders') }}" class="menu-link">
                <div class="menu-link-icon">📦</div>
                <div>{{ __('messages.manage_orders') }}</div>
            </a>
            <a href="{{ route('admin.users') }}" class="menu-link">
                <div class="menu-link-icon">👥</div>
                <div>{{ __('messages.manage_users') }}</div>
            </a>
            <a href="{{ route('admin.products.create') }}" class="menu-link">
                <div class="menu-link-icon">➕</div>
                <div>{{ __('messages.add_new_product') }}</div>
            </a>
        </div>
    </div>

    <!-- Pending Orders -->
    @if($latestOrders->count() > 0)
        <div class="admin-section">
            <div class="section-title">⏳ {{ __('messages.latest_pending_orders') }}</div>

            <table class="orders-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.order') }}</th>
                        <th>{{ __('messages.customer_name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.amount') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestOrders as $order)
                        <tr>
                            <td style="color: #1e293b;">#{{ $order->id }}</td>
                            <td style="color: #1e293b;">{{ $order->user->name }}</td>
                            <td style="font-size: 0.9rem; color: #1e293b;">{{ $order->user->email }}</td>
                            <td style="color: #1e293b;"><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                            <td style="color: #1e293b;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ trans_status($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">{{ __('messages.show') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 1rem;">
                <a href="{{ route('admin.orders') }}" class="btn btn-secondary">{{ __('messages.view_all_orders') }}</a>
            </div>
        </div>
    @else
        <div class="admin-section">
            <div class="empty-state">
                <p>🎉 {{ __('messages.no_pending_orders') }}</p>
            </div>
        </div>
    @endif
</div>
@endsection

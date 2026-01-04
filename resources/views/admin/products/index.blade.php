@extends('layouts.app')

@section('title', __('messages.manage_products'))

@section('content')
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin:2rem 0 1rem;">
        <h1>{{ __('messages.products') }}</h1>
        <a href="{{ route('admin.products.create') }}" style="padding:.7rem 1rem; background:linear-gradient(135deg, #0b1e3b 0%, #1a2f5a 100%); color:#fff; border-radius:6px; text-decoration:none;">+ {{ __('messages.add_product') }}</a>
    </div>

    @if($products->count())
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; background:#fff;">
                <thead>
                    <tr style="background:#f5f5f5; text-align:right;">
                        <th style="padding:10px; border-bottom:1px solid #eee;">#</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.product_name') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.price') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.stock') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">Status</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr style="color: #2c3e50;">
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $product->id }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $product->name }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ number_format($product->price, 2) }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $product->stock }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $product->is_active ? __('messages.available') : 'Inactive' }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0; display:flex; gap:.5rem;">
                                <a href="{{ route('admin.products.edit', $product) }}" style="color:#1a2f5a; text-decoration:none;">{{ __('messages.edit') }}</a>
                                <form action="{{ route('admin.products.delete', $product) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm') }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#c0392b; cursor:pointer;">{{ __('messages.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $products->links() }}
        </div>
    @else
        <p>{{ __('messages.no_data') }}</p>
    @endif
</div>
@endsection


@extends('layouts.app')

@section('title', __('messages.add_product'))

@section('content')
<div class="container">
    <h1 style="margin: 2rem 0 1rem;">{{ __('messages.add_product') }}</h1>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        @csrf
        <div style="margin-bottom: 1rem;">
            <label style="display:block; margin-bottom: .3rem; color: #2c3e50; font-weight: bold;">{{ __('messages.product_name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width:100%; padding:.6rem; border:1px solid #ddd; border-radius:6px;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display:block; margin-bottom: .3rem; color: #2c3e50; font-weight: bold;">{{ __('messages.description') }} (Short)</label>
            <textarea name="short_description" rows="2" required style="width:100%; padding:.6rem; border:1px solid #ddd; border-radius:6px;">{{ old('short_description') }}</textarea>
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display:block; margin-bottom: .3rem; color: #2c3e50; font-weight: bold;">{{ __('messages.description') }}</label>
            <textarea name="description" rows="4" required style="width:100%; padding:.6rem; border:1px solid #ddd; border-radius:6px;">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1rem;">
            <div style="flex:1; min-width:200px;">
                <label style="display:block; margin-bottom: .3rem; color: #2c3e50; font-weight: bold;">{{ __('messages.price') }}</label>
                <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required style="width:100%; padding:.6rem; border:1px solid #ddd; border-radius:6px;">
            </div>
            <div style="flex:1; min-width:200px;">
                <label style="display:block; margin-bottom: .3rem; color: #2c3e50; font-weight: bold;">{{ __('messages.stock') }}</label>
                <input type="number" name="stock" min="0" value="{{ old('stock') }}" required style="width:100%; padding:.6rem; border:1px solid #ddd; border-radius:6px;">
            </div>
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display:block; margin-bottom: .3rem; color: #2c3e50; font-weight: bold;">{{ __('messages.image') }}</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <div style="margin-bottom: 1.5rem; display:flex; align-items:center; gap:.5rem;">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
            <label style="color: #2c3e50;">{{ __('messages.available') }}</label>
        </div>

        <button type="submit" style="padding: .8rem 1.4rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:#fff; border:none; border-radius:6px; cursor:pointer;">{{ __('messages.save') }}</button>
    </form>
</div>
@endsection

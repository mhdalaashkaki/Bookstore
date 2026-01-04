@extends('layouts.app')

@section('title', __('messages.manage_users'))

@section('content')
<div class="container">
    <h1 style="margin: 2rem 0 1rem;">{{ __('messages.users') }}</h1>

    @if($users->count())
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; background:#fff;">
                <thead>
                    <tr style="background:#f5f5f5; text-align:right;">
                        <th style="padding:10px; border-bottom:1px solid #eee;">#</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">Name</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.email') }}</th>
                        <th style="padding:10px; border-bottom:1px solid #eee;">{{ __('messages.created_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="color: #2c3e50;">
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $user->id }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $user->name }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $user->email }}</td>
                            <td style="padding:10px; border-bottom:1px solid #f0f0f0;">{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">{{ $users->links() }}</div>
    @else
        <p>{{ __('messages.no_data') }}</p>
    @endif
</div>
@endsection

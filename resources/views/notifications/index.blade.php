@extends('layouts.app')

@section('title', 'Notifications - Complaint Management System')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h1 class="card-title">Notifications</h1>
            @if($notifications->where('status', 'unread')->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-secondary btn-sm">Mark All as Read</button>
                </form>
            @endif
        </div>
        <p style="color: #6b7280; margin-top: 0.5rem;">Stay updated on your complaint status changes</p>
    </div>

    @if($notifications->count() > 0)
        <div style="space-y: 1rem;">
            @foreach($notifications as $notification)
            <div style="padding: 1.5rem; border-radius: 6px; border-left: 4px solid {{ $notification->status === 'unread' ? '#f59e0b' : '#d1d5db' }}; 
                        background: {{ $notification->status === 'unread' ? '#fefbf3' : '#f9fafb' }}; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: between; align-items: start; gap: 1rem;">
                    <div style="flex: 1;">
                        <p style="margin: 0 0 0.5rem 0; line-height: 1.5;">{{ $notification->message }}</p>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <small style="color: #6b7280;">{{ $notification->created_at->diffForHumans() }}</small>
                            @if($notification->status === 'unread')
                                <span class="badge badge-unread">Unread</span>
                            @else
                                <span class="badge badge-read">Read</span>
                            @endif
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 0.5rem;">
                        @if($notification->status === 'unread')
                            <form action="{{ route('notifications.read', $notification) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-sm">Mark as Read</button>
                            </form>
                        @endif
                        
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" style="display: inline;"
                              onsubmit="return confirm('Are you sure you want to delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 3rem;">
            <p style="color: #6b7280; font-size: 1.125rem; margin-bottom: 2rem;">
                You don't have any notifications yet.
            </p>
            <a href="{{ route('complaints.create') }}" class="btn btn-primary">
                Submit a Complaint
            </a>
        </div>
    @endif
</div>
@endsection

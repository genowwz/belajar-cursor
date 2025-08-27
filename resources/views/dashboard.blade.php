@extends('layouts.app')

@section('title', 'Dashboard - Complaint Management System')

@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Welcome, {{ auth()->user()->name }}!</h1>
        <p style="color: #6b7280; margin-top: 0.5rem;">Here's an overview of your complaints and notifications</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
        <div style="background: #eff6ff; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <h3 style="color: #1d4ed8; margin-bottom: 0.5rem;">Total Complaints</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #1e40af;">{{ $complaints->count() }}</p>
        </div>
        
        <div style="background: #fef3c7; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <h3 style="color: #92400e; margin-bottom: 0.5rem;">Pending</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #b45309;">{{ $complaints->where('status', 'pending')->count() }}</p>
        </div>
        
        <div style="background: #d1fae5; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <h3 style="color: #065f46; margin-bottom: 0.5rem;">Resolved</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #047857;">{{ $complaints->where('status', 'resolved')->count() }}</p>
        </div>
        
        <div style="background: #fecaca; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <h3 style="color: #991b1b; margin-bottom: 0.5rem;">Unread Notifications</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #dc2626;">{{ $notifications->count() }}</p>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <a href="{{ route('complaints.create') }}" class="btn btn-primary">
            Submit New Complaint
        </a>
        <a href="{{ route('complaints.index') }}" class="btn btn-secondary">
            View All Complaints
        </a>
        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
            View Notifications
        </a>
    </div>
</div>

@if($complaints->count() > 0)
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Complaints</h2>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints->take(5) as $complaint)
            <tr>
                <td>{{ $complaint->title }}</td>
                <td>
                    <span class="badge badge-{{ $complaint->status }}">
                        {{ ucfirst($complaint->status) }}
                    </span>
                </td>
                <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($notifications->count() > 0)
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Notifications</h2>
    </div>
    
    <div style="space-y: 1rem;">
        @foreach($notifications as $notification)
        <div style="padding: 1rem; background: #f9fafb; border-radius: 6px; border-left: 4px solid #fbbf24; margin-bottom: 1rem;">
            <p>{{ $notification->message }}</p>
            <small style="color: #6b7280;">{{ $notification->created_at->diffForHumans() }}</small>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

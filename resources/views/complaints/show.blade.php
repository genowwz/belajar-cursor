@extends('layouts.app')

@section('title', $complaint->title . ' - Complaint Management System')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 class="card-title">{{ $complaint->title }}</h1>
                <p style="color: #6b7280; margin-top: 0.5rem;">
                    Submitted by {{ $complaint->user->name }} on {{ $complaint->created_at->format('M d, Y \a\t g:i A') }}
                </p>
            </div>
            <span class="badge badge-{{ $complaint->status }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ ucfirst($complaint->status) }}
            </span>
        </div>
    </div>

    <div style="margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem; color: #374151;">Description</h3>
        <div style="background: #f9fafb; padding: 1.5rem; border-radius: 6px; border-left: 4px solid #2563eb;">
            <p style="line-height: 1.6; margin: 0; white-space: pre-wrap;">{{ $complaint->description }}</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div>
            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Status</label>
            <span class="badge badge-{{ $complaint->status }}">{{ ucfirst($complaint->status) }}</span>
        </div>
        
        <div>
            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Created</label>
            <p style="margin: 0; color: #6b7280;">{{ $complaint->created_at->format('M d, Y \a\t g:i A') }}</p>
        </div>
        
        <div>
            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Last Updated</label>
            <p style="margin: 0; color: #6b7280;">{{ $complaint->updated_at->format('M d, Y \a\t g:i A') }}</p>
        </div>
        
        <div>
            <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Complaint ID</label>
            <p style="margin: 0; color: #6b7280;">#{{ $complaint->id }}</p>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
        <a href="{{ route('complaints.index') }}" class="btn btn-secondary">Back to Complaints</a>
        
        @if(!auth()->user()->isAdmin() && $complaint->user_id === auth()->id())
            <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" style="display: inline;"
                  onsubmit="return confirm('Are you sure you want to delete this complaint?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Complaint</button>
            </form>
        @endif
    </div>
</div>
@endsection

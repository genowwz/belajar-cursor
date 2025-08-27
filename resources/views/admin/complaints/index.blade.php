@extends('layouts.app')

@section('title', 'Manage Complaints - Admin Dashboard')

@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Manage Complaints</h1>
        <p style="color: #6b7280; margin-top: 0.5rem;">Review and update complaint statuses</p>
    </div>

    @if($complaints->count() > 0)
        <div style="margin-bottom: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div style="background: #eff6ff; padding: 1rem; border-radius: 6px; text-align: center;">
                    <h4 style="color: #1d4ed8; margin-bottom: 0.5rem;">Total</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: #1e40af; margin: 0;">{{ $complaints->count() }}</p>
                </div>
                <div style="background: #fef3c7; padding: 1rem; border-radius: 6px; text-align: center;">
                    <h4 style="color: #92400e; margin-bottom: 0.5rem;">Pending</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: #b45309; margin: 0;">{{ $complaints->where('status', 'pending')->count() }}</p>
                </div>
                <div style="background: #d1fae5; padding: 1rem; border-radius: 6px; text-align: center;">
                    <h4 style="color: #065f46; margin-bottom: 0.5rem;">Resolved</h4>
                    <p style="font-size: 1.5rem; font-weight: bold; color: #047857; margin: 0;">{{ $complaints->where('status', 'resolved')->count() }}</p>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $complaint)
                <tr>
                    <td>#{{ $complaint->id }}</td>
                    <td>{{ $complaint->user->name }}</td>
                    <td>
                        <strong>{{ $complaint->title }}</strong>
                        <br>
                        <small style="color: #6b7280;">{{ Str::limit($complaint->description, 50) }}</small>
                    </td>
                    <td>
                        <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" 
                                    style="padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.875rem;">
                                <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </form>
                    </td>
                    <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-primary btn-sm">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 3rem;">
            <p style="color: #6b7280; font-size: 1.125rem;">
                No complaints have been submitted yet.
            </p>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'My Complaints - Complaint Management System')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h1 class="card-title">My Complaints</h1>
            <a href="{{ route('complaints.create') }}" class="btn btn-primary">
                Submit New Complaint
            </a>
        </div>
        <p style="color: #6b7280; margin-top: 0.5rem;">Track and manage your submitted complaints</p>
    </div>

    @if($complaints->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $complaint)
                <tr>
                    <td>
                        <strong>{{ $complaint->title }}</strong>
                        <br>
                        <small style="color: #6b7280;">{{ Str::limit($complaint->description, 60) }}</small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $complaint->status }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </td>
                    <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                    <td>{{ $complaint->updated_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-primary btn-sm">View</a>
                            <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" style="display: inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this complaint?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 3rem;">
            <p style="color: #6b7280; font-size: 1.125rem; margin-bottom: 2rem;">
                You haven't submitted any complaints yet.
            </p>
            <a href="{{ route('complaints.create') }}" class="btn btn-primary">
                Submit Your First Complaint
            </a>
        </div>
    @endif
</div>
@endsection

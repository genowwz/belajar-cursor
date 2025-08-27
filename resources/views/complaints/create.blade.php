@extends('layouts.app')

@section('title', 'Submit Complaint - Complaint Management System')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h1 class="card-title">Submit New Complaint</h1>
        <p style="color: #6b7280; margin-top: 0.5rem;">Describe your issue in detail so we can help you better</p>
    </div>

    <form action="{{ route('complaints.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title" class="form-label">Complaint Title *</label>
            <input type="text" id="title" name="title" class="form-control" 
                   value="{{ old('title') }}" required
                   placeholder="Brief description of your complaint">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Detailed Description *</label>
            <textarea id="description" name="description" class="form-control" required
                      placeholder="Please provide a detailed description of your complaint, including any relevant information that might help us resolve the issue...">{{ old('description') }}</textarea>
        </div>

        <div style="background: #f3f4f6; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 0.5rem; color: #374151;">Tips for a good complaint:</h4>
            <ul style="margin: 0; color: #6b7280; font-size: 0.875rem;">
                <li>Be specific about the issue you're experiencing</li>
                <li>Include relevant dates, times, and locations</li>
                <li>Mention any steps you've already taken to resolve the issue</li>
                <li>Attach or mention any relevant documentation if applicable</li>
            </ul>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('complaints.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Complaint</button>
        </div>
    </form>
</div>
@endsection

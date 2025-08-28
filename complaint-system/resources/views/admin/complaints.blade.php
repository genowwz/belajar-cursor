@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Complaints</h1>
        <p class="mt-2 text-gray-600">View and manage all complaints in the system.</p>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('admin.complaints') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           class="form-input" placeholder="Search complaints...">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="form-input">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
                
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select id="priority" name="priority" class="form-input">
                        <option value="">All Priorities</option>
                        <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category" name="category" class="form-input">
                        <option value="">All Categories</option>
                        <option value="Service" {{ request('category') == 'Service' ? 'selected' : '' }}>Service</option>
                        <option value="Product" {{ request('category') == 'Product' ? 'selected' : '' }}>Product</option>
                        <option value="Delivery" {{ request('category') == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                        <option value="Billing" {{ request('category') == 'Billing' ? 'selected' : '' }}>Billing</option>
                        <option value="Technical" {{ request('category') == 'Technical' ? 'selected' : '' }}>Technical</option>
                        <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="btn-primary">
                    Apply Filters
                </button>
                <a href="{{ route('admin.complaints') }}" class="btn-secondary">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Complaints List -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">
                    Complaints ({{ $complaints->total() }})
                </h2>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-500">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
        
        @if($complaints->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($complaints as $complaint)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $complaint->title }}
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($complaint->priority === 'High') bg-red-100 text-red-800
                                        @elseif($complaint->priority === 'Medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $complaint->priority }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($complaint->status === 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($complaint->status === 'In Progress') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $complaint->status }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Category:</span> {{ $complaint->category }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Submitted:</span> {{ $complaint->created_at->format('M d, Y H:i') }}
                                        </p>
                                        @if($complaint->resolved_at)
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Resolved:</span> {{ $complaint->resolved_at->format('M d, Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        @if($complaint->user)
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">User:</span> {{ $complaint->user->name }} ({{ $complaint->user->title }})
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Email:</span> {{ $complaint->user->email }}
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Guest User:</span> {{ $complaint->user_name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Email:</span> {{ $complaint->user_email }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($complaint->description)
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-medium">Description:</span> {{ Str::limit($complaint->description, 200) }}
                                        </p>
                                    </div>
                                @endif
                                
                                @if($complaint->attachments->count() > 0)
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Attachments:</span>
                                        </p>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach($complaint->attachments as $attachment)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a2 2 0 00-2.828-2.828z"></path>
                                                    </svg>
                                                    {{ $attachment->original_name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                @if($complaint->admin_notes)
                                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                        <p class="text-sm text-blue-800">
                                            <span class="font-medium">Admin Notes:</span> {{ $complaint->admin_notes }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex flex-col space-y-2">
                                <!-- Status Update -->
                                <div class="flex items-center space-x-2">
                                    <select id="status-{{ $complaint->id }}" class="form-input text-sm">
                                        <option value="Pending" {{ $complaint->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ $complaint->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Resolved" {{ $complaint->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                    <button onclick="updateStatus('{{ $complaint->id }}')" class="btn-primary text-sm">
                                        Update
                                    </button>
                                </div>
                                
                                <!-- Admin Notes -->
                                <div class="flex items-center space-x-2">
                                    <input type="text" id="notes-{{ $complaint->id }}" 
                                           value="{{ $complaint->admin_notes }}" 
                                           placeholder="Add admin notes..."
                                           class="form-input text-sm">
                                    <button onclick="updateNotes('{{ $complaint->id }}')" class="btn-secondary text-sm">
                                        Save Notes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $complaints->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No complaints found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search terms.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function updateStatus(complaintId) {
    const status = document.getElementById(`status-${complaintId}`).value;
    
    fetch(`/admin/complaints/${complaintId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
}

function updateNotes(complaintId) {
    const notes = document.getElementById(`notes-${complaintId}`).value;
    
    fetch(`/admin/complaints/${complaintId}/notes`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ admin_notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating notes');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating notes');
    });
}
</script>
@endpush
@endsection
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Complaints</h1>
                <p class="mt-2 text-gray-600">Track the status and progress of your submitted complaints.</p>
            </div>
            <a href="{{ route('complaints.create') }}" class="btn-primary">
                Submit New Complaint
            </a>
        </div>
        
        <!-- User Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Complaints</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->complaint_count }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Current Title</p>
                        <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->title }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Resolved</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $complaints->where('status', 'Resolved')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $complaints->where('status', 'Pending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaints List -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Recent Complaints</h2>
        </div>
        
        @if($complaints->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($complaints as $complaint)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <a href="{{ route('complaints.show', $complaint) }}" class="hover:text-blue-600">
                                            {{ $complaint->title }}
                                        </a>
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
                                <div class="mt-2 flex items-center text-sm text-gray-500 space-x-4">
                                    <span>{{ $complaint->category }}</span>
                                    <span>•</span>
                                    <span>{{ $complaint->created_at->format('M d, Y') }}</span>
                                    @if($complaint->attachments->count() > 0)
                                        <span>•</span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a2 2 0 00-2.828-2.828z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20l6.586-6.586a2 2 0 102.828-2.828L8.828 17.172a2 2 0 00-2.828 2.828z"></path>
                                            </svg>
                                            {{ $complaint->attachments->count() }} attachment(s)
                                        </span>
                                    @endif
                                </div>
                                @if($complaint->admin_notes)
                                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                        <p class="text-sm text-blue-800">
                                            <strong>Admin Note:</strong> {{ Str::limit($complaint->admin_notes, 150) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('complaints.show', $complaint) }}" class="btn-secondary text-sm">
                                    View
                                </a>
                                <a href="{{ route('complaints.edit', $complaint) }}" class="btn-secondary text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $complaints->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No complaints yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by submitting your first complaint.</p>
                <div class="mt-6">
                    <a href="{{ route('complaints.create') }}" class="btn-primary">
                        Submit Complaint
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Dashboard - Complaint Management System')

@section('content')
<!-- Welcome Header -->
<div class="mb-8">
    <h1 class="text-3xl font-light text-gray-900 mb-2">
        Welcome back, <span class="font-medium">{{ auth()->user()->name }}</span>!
    </h1>
    <p class="text-lg text-gray-600">Here's an overview of your complaints and notifications</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Complaints</p>
                <p class="text-3xl font-light text-gray-900">{{ $complaints->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                <p class="text-3xl font-light text-amber-600">{{ $complaints->where('status', 'pending')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Resolved</p>
                <p class="text-3xl font-light text-green-600">{{ $complaints->where('status', 'resolved')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Unread Notifications</p>
                <p class="text-3xl font-light text-red-600">{{ $notifications->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM7.07 6.93A10 10 0 1017.07 6.93L7.07 6.93z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex flex-col sm:flex-row gap-4 mb-8">
    <a href="{{ route('complaints.create') }}" 
       class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Submit New Complaint
    </a>
    <a href="{{ route('complaints.index') }}" 
       class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        View All Complaints
    </a>
    <a href="{{ route('notifications.index') }}" 
       class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM7.07 6.93A10 10 0 1017.07 6.93L7.07 6.93z"></path>
        </svg>
        View Notifications
    </a>
</div>

@if($complaints->count() > 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Recent Complaints</h2>
        <p class="text-sm text-gray-600 mt-1">Your latest submitted complaints</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($complaints->take(5) as $complaint)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $complaint->title }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($complaint->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        @elseif($complaint->status === 'resolved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $complaint->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('complaints.show', $complaint) }}" 
                           class="text-blue-600 hover:text-blue-900 transition-colors duration-150">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($notifications->count() > 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Recent Notifications</h2>
        <p class="text-sm text-gray-600 mt-1">Latest updates on your complaints</p>
    </div>
    
    <div class="divide-y divide-gray-200">
        @foreach($notifications as $notification)
        <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-2 h-2 bg-amber-400 rounded-full mt-2"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900">{{ $notification->message }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

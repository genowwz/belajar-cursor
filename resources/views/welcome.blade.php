@extends('layouts.app')

@section('title', 'Welcome - Complaint Management System')

@section('content')
<!-- Hero Section -->
<div class="text-center max-w-4xl mx-auto mb-16">
    <h1 class="text-4xl md:text-6xl font-light text-gray-900 mb-6 text-balance">
        Welcome to 
        <span class="font-medium text-blue-600">Complaint Management</span>
    </h1>
    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto text-balance leading-relaxed">
        Submit and track your complaints with ease. Our system ensures your concerns are heard and addressed promptly.
    </p>

    @guest
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center px-8 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                Login
            </a>
            <a href="{{ route('register') }}" 
               class="inline-flex items-center px-8 py-3 text-base font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                Get Started
            </a>
        </div>
    @else
        @if(auth()->user()->isAdmin())
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 max-w-lg mx-auto">
                <h2 class="text-2xl font-semibold text-gray-900 mb-3">Admin Dashboard</h2>
                <p class="text-gray-600 mb-6">Manage and resolve complaints from users.</p>
                <a href="{{ route('admin.complaints.index') }}" 
                   class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                    Manage Complaints
                </a>
            </div>
        @else
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 max-w-lg mx-auto">
                <h2 class="text-2xl font-semibold text-gray-900 mb-3">Welcome Back</h2>
                <p class="text-gray-600 mb-6">Submit new complaints and track existing ones.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('complaints.create') }}" 
                       class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                        Submit Complaint
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                        View Dashboard
                    </a>
                </div>
            </div>
        @endif
    @endguest
</div>

<!-- Features Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 card-hover">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-3">Easy Submission</h3>
        <p class="text-gray-600 leading-relaxed">Submit your complaints quickly and easily through our simple, intuitive form interface.</p>
    </div>
    
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 card-hover">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-3">Real-time Tracking</h3>
        <p class="text-gray-600 leading-relaxed">Track the status of your complaints in real-time and receive instant notifications about updates.</p>
    </div>
    
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 card-hover">
        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-3">Quick Resolution</h3>
        <p class="text-gray-600 leading-relaxed">Our dedicated admin team works efficiently to resolve your complaints as quickly as possible.</p>
    </div>
</div>

<!-- Stats Section (Optional - if you have data) -->
<div class="mt-20 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-8 md:p-12">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-light text-gray-900 mb-4">Trusted by Our Community</h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Join thousands of users who trust our platform for efficient complaint management.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div>
            <div class="text-4xl font-light text-blue-600 mb-2">24/7</div>
            <div class="text-gray-700 font-medium">Support Available</div>
        </div>
        <div>
            <div class="text-4xl font-light text-green-600 mb-2">98%</div>
            <div class="text-gray-700 font-medium">Resolution Rate</div>
        </div>
        <div>
            <div class="text-4xl font-light text-purple-600 mb-2">2hrs</div>
            <div class="text-gray-700 font-medium">Average Response Time</div>
        </div>
    </div>
</div>
@endsection

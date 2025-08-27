@extends('layouts.app')

@section('title', 'Welcome - Complaint Management System')

@section('content')
<div class="card" style="text-align: center; max-width: 800px; margin: 2rem auto;">
    <div class="card-header">
        <h1 class="card-title" style="font-size: 2.5rem; margin-bottom: 1rem;">
            Welcome to Complaint Management System
        </h1>
        <p style="font-size: 1.125rem; color: #6b7280;">
            Submit and track your complaints with ease. Our system ensures your concerns are heard and addressed promptly.
        </p>
    </div>

    @guest
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-secondary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                Register
            </a>
        </div>
    @else
        @if(auth()->user()->isAdmin())
            <div>
                <h2 style="margin-bottom: 1rem;">Admin Dashboard</h2>
                <p style="margin-bottom: 2rem; color: #6b7280;">Manage and resolve complaints from users.</p>
                <a href="{{ route('admin.complaints.index') }}" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                    Manage Complaints
                </a>
            </div>
        @else
            <div>
                <h2 style="margin-bottom: 1rem;">User Dashboard</h2>
                <p style="margin-bottom: 2rem; color: #6b7280;">Submit new complaints and track existing ones.</p>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('complaints.create') }}" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                        Submit Complaint
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                        View Dashboard
                    </a>
                </div>
            </div>
        @endif
    @endguest
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
    <div class="card">
        <h3 style="color: #2563eb; margin-bottom: 1rem;">Easy Submission</h3>
        <p style="color: #6b7280;">Submit your complaints quickly and easily through our simple form interface.</p>
    </div>
    
    <div class="card">
        <h3 style="color: #2563eb; margin-bottom: 1rem;">Real-time Tracking</h3>
        <p style="color: #6b7280;">Track the status of your complaints in real-time and receive notifications.</p>
    </div>
    
    <div class="card">
        <h3 style="color: #2563eb; margin-bottom: 1rem;">Quick Resolution</h3>
        <p style="color: #6b7280;">Our admin team works to resolve your complaints as quickly as possible.</p>
    </div>
</div>
@endsection

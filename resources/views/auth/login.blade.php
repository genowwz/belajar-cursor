@extends('layouts.app')

@section('title', 'Login - Complaint Management System')

@section('content')
<div class="card" style="max-width: 400px; margin: 2rem auto;">
    <div class="card-header">
        <h1 class="card-title">Login</h1>
        <p style="color: #6b7280; margin-top: 0.5rem;">Access your complaint management account</p>
    </div>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="remember" style="margin: 0;">
                Remember me
            </label>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Login
            </button>
        </div>
    </form>

    <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
        <p style="color: #6b7280;">
            Don't have an account? 
            <a href="{{ route('register') }}" style="color: #2563eb; text-decoration: none;">Register here</a>
        </p>
    </div>
</div>
@endsection

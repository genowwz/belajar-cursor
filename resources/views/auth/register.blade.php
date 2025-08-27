@extends('layouts.app')

@section('title', 'Register - Complaint Management System')

@section('content')
<div class="card" style="max-width: 400px; margin: 2rem auto;">
    <div class="card-header">
        <h1 class="card-title">Register</h1>
        <p style="color: #6b7280; margin-top: 0.5rem;">Create your complaint management account</p>
    </div>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <small style="color: #6b7280; font-size: 0.875rem;">Minimum 6 characters</small>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Register
            </button>
        </div>
    </form>

    <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
        <p style="color: #6b7280;">
            Already have an account? 
            <a href="{{ route('login') }}" style="color: #2563eb; text-decoration: none;">Login here</a>
        </p>
    </div>
</div>
@endsection

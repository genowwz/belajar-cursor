@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Submit a Complaint</h1>
        <p class="mt-2 text-gray-600">Tell us about your issue and we'll work to resolve it quickly.</p>
    </div>

    <div class="card">
        <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Title -->
            <div>
                <label for="title" class="form-label">Complaint Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       class="form-input @error('title') border-red-500 @enderror"
                       placeholder="Brief description of your complaint">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" required
                        class="form-input @error('category') border-red-500 @enderror">
                    <option value="">Select a category</option>
                    <option value="Service" {{ old('category') == 'Service' ? 'selected' : '' }}>Service</option>
                    <option value="Product" {{ old('category') == 'Product' ? 'selected' : '' }}>Product</option>
                    <option value="Delivery" {{ old('category') == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="Billing" {{ old('category') == 'Billing' ? 'selected' : '' }}>Billing</option>
                    <option value="Technical" {{ old('category') == 'Technical' ? 'selected' : '' }}>Technical</option>
                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="form-label">Priority Level</label>
                <select id="priority" name="priority" required
                        class="form-input @error('priority') border-red-500 @enderror">
                    <option value="">Select priority level</option>
                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                </select>
                @error('priority')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="form-label">Detailed Description</label>
                <textarea id="description" name="description" rows="6" required
                          class="form-input @error('description') border-red-500 @enderror"
                          placeholder="Please provide a detailed description of your complaint, including any relevant details, dates, and what you'd like us to do to resolve the issue.">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Attachments -->
            <div>
                <label for="attachments" class="form-label">Attachments (Optional)</label>
                <input type="file" id="attachments" name="attachments[]" multiple
                       class="form-input @error('attachments.*') border-red-500 @enderror"
                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                <p class="mt-1 text-sm text-gray-500">
                    You can upload images, PDFs, or documents. Maximum file size: 2MB per file.
                </p>
                @error('attachments.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Guest User Fields -->
            @guest
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Since you're not logged in, please provide your contact information so we can reach you about your complaint.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   class="form-input @error('name') border-red-500 @enderror"
                                   placeholder="Your full name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="form-input @error('email') border-red-500 @enderror"
                                   placeholder="your.email@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Tip:</strong> Consider <a href="{{ route('register') }}" class="underline">creating an account</a> to track your complaints and earn user titles!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-6">
                <a href="{{ route('home') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Submit Complaint
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
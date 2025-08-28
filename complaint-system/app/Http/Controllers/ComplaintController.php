<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $complaints = Auth::user()->complaints()->latest()->paginate(10);
            return view('complaints.index', compact('complaints'));
        }
        
        return redirect()->route('login')->with('message', 'Please log in to view your complaints.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|in:Service,Product,Delivery,Billing,Technical,Other',
            'priority' => 'required|in:Low,Medium,High',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:2048'
        ]);

        $complaintData = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'Pending'
        ];

        if (Auth::check()) {
            $complaintData['user_id'] = Auth::id();
            $complaintData['user_email'] = Auth::user()->email;
            $complaintData['user_name'] = Auth::user()->name;
        } else {
            $complaintData['user_email'] = $request->email ?? 'anonymous@example.com';
            $complaintData['user_name'] = $request->name ?? 'Anonymous';
        }

        $complaint = Complaint::create($complaintData);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                
                Attachment::create([
                    'complaint_id' => $complaint->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Update user complaint count and title if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $user->increment('complaint_count');
            $user->updateTitle();
        }

        return redirect()->route('home')->with('success', 'Complaint submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        if (Auth::check() && $complaint->user_id === Auth::id()) {
            return view('complaints.show', compact('complaint'));
        }
        
        abort(403, 'Unauthorized access.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Complaint $complaint)
    {
        if (Auth::check() && $complaint->user_id === Auth::id()) {
            return view('complaints.edit', compact('complaint'));
        }
        
        abort(403, 'Unauthorized access.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        if (Auth::check() && $complaint->user_id === Auth::id()) {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'category' => 'required|in:Service,Product,Delivery,Billing,Technical,Other',
                'priority' => 'required|in:Low,Medium,High',
            ]);

            $complaint->update($request->only(['title', 'description', 'category', 'priority']));

            return redirect()->route('complaints.show', $complaint)->with('success', 'Complaint updated successfully!');
        }
        
        abort(403, 'Unauthorized access.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Complaint $complaint)
    {
        if (Auth::check() && $complaint->user_id === Auth::id()) {
            // Delete attachments
            foreach ($complaint->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            }
            
            $complaint->delete();
            
            // Update user complaint count and title
            $user = Auth::user();
            $user->decrement('complaint_count');
            $user->updateTitle();
            
            return redirect()->route('complaints.index')->with('success', 'Complaint deleted successfully!');
        }
        
        abort(403, 'Unauthorized access.');
    }
}

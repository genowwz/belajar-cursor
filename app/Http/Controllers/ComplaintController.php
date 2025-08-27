<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Complaint;
use App\Models\Notification;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints for regular users.
     */
    public function index()
    {
        $user = Auth::user();
        $complaints = $user->complaints()->latest()->get();

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Display a listing of all complaints for admins.
     */
    public function adminIndex()
    {
        $complaints = Complaint::with('user')->latest()->get();
        
        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new complaint.
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('complaints.index')->with('success', 'Complaint submitted successfully!');
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        // Ensure user can only view their own complaints (unless admin)
        if (!Auth::user()->isAdmin() && $complaint->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Update the complaint status (admin only).
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        // Ensure only admins can update status
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,resolved',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $oldStatus = $complaint->status;
        $newStatus = $request->status;

        $complaint->update(['status' => $newStatus]);

        // Create notification for the user if status changed
        if ($oldStatus !== $newStatus) {
            Notification::create([
                'user_id' => $complaint->user_id,
                'message' => "Your complaint '{$complaint->title}' has been updated to {$newStatus}.",
                'status' => 'unread',
                'created_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Complaint status updated successfully!');
    }

    /**
     * Remove the specified complaint from storage.
     */
    public function destroy(Complaint $complaint)
    {
        // Ensure user can only delete their own complaints (unless admin)
        if (!Auth::user()->isAdmin() && $complaint->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $complaint->delete();

        return redirect()->route('complaints.index')->with('success', 'Complaint deleted successfully!');
    }
}

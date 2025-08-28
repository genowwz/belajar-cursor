<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $totalComplaints = Complaint::count();
        $pendingComplaints = Complaint::where('status', 'Pending')->count();
        $inProgressComplaints = Complaint::where('status', 'In Progress')->count();
        $resolvedComplaints = Complaint::where('status', 'Resolved')->count();
        $highPriorityComplaints = Complaint::where('priority', 'High')->count();
        
        $recentComplaints = Complaint::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalComplaints',
            'pendingComplaints',
            'inProgressComplaints',
            'resolvedComplaints',
            'highPriorityComplaints',
            'recentComplaints'
        ));
    }

    /**
     * Show all complaints for admin management.
     */
    public function complaints(Request $request)
    {
        $query = Complaint::with('user')->latest();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('user_email', 'like', "%{$search}%");
            });
        }
        
        $complaints = $query->paginate(15);
        
        return view('admin.complaints', compact('complaints'));
    }

    /**
     * Update complaint status.
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Resolved'
        ]);
        
        $complaint->update([
            'status' => $request->status,
            'resolved_at' => $request->status === 'Resolved' ? now() : null
        ]);
        
        // Send notification to user if they have an account
        if ($complaint->user_id) {
            // TODO: Implement notification system
        }
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    /**
     * Update admin notes for a complaint.
     */
    public function updateNotes(Request $request, Complaint $complaint)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);
        
        $complaint->update(['admin_notes' => $request->admin_notes]);
        
        return response()->json(['success' => true, 'message' => 'Notes updated successfully']);
    }
}

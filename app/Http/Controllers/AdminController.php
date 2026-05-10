<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Report;
use App\Models\User;
use App\Models\AuditTrail;
use App\Models\MaintenanceTask;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $reports = Report::latest()->get();
        $stats = [
            'total' => $reports->count(),
            'pending' => $reports->where('status', 'pending')->count(),
            'valid' => $reports->where('status', 'valid')->count(),
            'in_progress' => $reports->where('status', 'in_progress')->count(),
            'completed' => $reports->where('status', 'completed')->count(),
            'invalid' => $reports->where('status', 'invalid')->count(),
            'duplicate' => $reports->where('status', 'duplicate')->count(),
        ];
        return view('admin.dashboard', compact('reports', 'stats'));
    }

    public function gis()
    {
        $reports = Report::all();
        return view('admin.gis', compact('reports'));
    }

    public function show(Report $report)
    {
        $staffs = User::where('role', 'staff')->get();
        return view('admin.reports.show', compact('report', 'staffs'));
    }

    public function verify(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:valid,invalid,duplicate',
            'admin_note' => 'nullable|string'
        ]);

        $oldStatus = $report->status;
        
        $report->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'],
            'verified_by' => Auth::id(),
            'verified_at' => now()
        ]);

        AuditTrail::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'action' => 'verified',
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'note' => $validated['admin_note']
        ]);

        return back()->with('success', 'Status laporan berhasil diubah.');
    }

    public function assign(Request $request, Report $report)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id'
        ]);

        MaintenanceTask::updateOrCreate(
            ['report_id' => $report->id],
            [
                'staff_id' => $validated['staff_id'],
                'assigned_by' => Auth::id(),
                'task_status' => 'assigned'
            ]
        );

        $oldStatus = $report->status;
        $report->update(['status' => 'in_progress']);

        AuditTrail::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'action' => 'assigned',
            'old_status' => $oldStatus,
            'new_status' => 'in_progress',
            'note' => 'Laporan ditugaskan ke staff.'
        ]);

        return back()->with('success', 'Laporan berhasil ditugaskan ke staff.');
    }

    public function audit()
    {
        $audits = AuditTrail::with(['report', 'user'])->latest()->get();
        return view('admin.audit', compact('audits'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Report;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class PelaporController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $reports = Report::where('reporter_id', $user->id)->latest()->get();
        
        $stats = [
            'total' => $reports->count(),
            'pending' => $reports->where('status', 'pending')->count(),
            'valid' => $reports->where('status', 'valid')->count(),
            'in_progress' => $reports->where('status', 'in_progress')->count(),
            'completed' => $reports->where('status', 'completed')->count(),
            'invalid' => $reports->where('status', 'invalid')->count(),
            'duplicate' => $reports->where('status', 'duplicate')->count(),
        ];

        return view('pelapor.dashboard', compact('reports', 'stats'));
    }

    public function create()
    {
        return view('pelapor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'facility_name' => 'required|string|max:255',
            'facility_type' => 'required|string',
            'building' => 'required|string',
            'floor' => 'required|string',
            'room' => 'required|string',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'severity_score' => 'required|integer|min:1|max:5',
            'academic_impact_score' => 'required|integer|min:1|max:5',
            'estimated_cost_score' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        $report = Report::create([
            'reporter_id' => Auth::id(),
            'title' => $validated['title'],
            'facility_name' => $validated['facility_name'],
            'facility_type' => $validated['facility_type'],
            'building' => $validated['building'],
            'floor' => $validated['floor'],
            'room' => $validated['room'],
            'description' => $validated['description'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'severity_score' => $validated['severity_score'],
            'academic_impact_score' => $validated['academic_impact_score'],
            'estimated_cost_score' => $validated['estimated_cost_score'],
            'photo' => $photoPath,
            'status' => 'pending'
        ]);

        AuditTrail::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'new_status' => 'pending',
            'note' => 'Laporan baru dibuat'
        ]);

        return redirect()->route('pelapor.dashboard')->with('success', 'Laporan berhasil dibuat!');
    }

    public function show(Report $report)
    {
        if ($report->reporter_id !== Auth::id()) {
            abort(403);
        }
        return view('pelapor.show', compact('report'));
    }
}

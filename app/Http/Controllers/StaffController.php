<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MaintenanceTask;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function dashboard()
    {
        $tasks = MaintenanceTask::where('staff_id', Auth::id())
            ->with(['report', 'report.sawResult'])
            // Sort by priority logic: if we have SAW result rank, sort by that
            ->get()
            ->sortBy(function($task) {
                return $task->report->sawResult ? $task->report->sawResult->rank_position : 9999;
            });
            
        return view('staff.dashboard', compact('tasks'));
    }

    public function show(MaintenanceTask $task)
    {
        if ($task->staff_id !== Auth::id()) {
            abort(403);
        }
        return view('staff.tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, MaintenanceTask $task)
    {
        if ($task->staff_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'task_status' => 'required|in:in_progress,completed',
            'staff_note' => 'nullable|string',
            'completion_photo' => 'nullable|image|max:2048'
        ]);

        $taskData = [
            'task_status' => $validated['task_status'],
            'staff_note' => $validated['staff_note']
        ];

        if ($validated['task_status'] === 'in_progress' && !$task->started_at) {
            $taskData['started_at'] = now();
        }

        if ($validated['task_status'] === 'completed') {
            $taskData['completed_at'] = now();
            if ($request->hasFile('completion_photo')) {
                $taskData['completion_photo'] = $request->file('completion_photo')->store('tasks', 'public');
            }
        }

        $task->update($taskData);

        // Update Report status if completed
        if ($validated['task_status'] === 'completed') {
            $task->report->update(['status' => 'completed']);
            AuditTrail::create([
                'report_id' => $task->report_id,
                'user_id' => Auth::id(),
                'action' => 'completed',
                'old_status' => 'in_progress',
                'new_status' => 'completed',
                'note' => 'Tugas telah diselesaikan oleh staff.'
            ]);
        }

        return back()->with('success', 'Status tugas berhasil diperbarui.');
    }
}

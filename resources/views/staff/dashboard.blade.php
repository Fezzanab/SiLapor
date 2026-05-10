@extends('layouts.app')

@section('title', 'Dashboard Staff - SiLapor')
@section('header', 'Tugas Maintenance')

@section('content')
<div class="neu-card p-6">
    <h3 class="text-xl font-bold mb-6">Daftar Tugas Saya</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-neu-border">
                    <th class="p-3 font-bold">Laporan</th>
                    <th class="p-3 font-bold">Lokasi</th>
                    <th class="p-3 font-bold text-center">Prioritas (SAW)</th>
                    <th class="p-3 font-bold">Status Tugas</th>
                    <th class="p-3 font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr class="border-b border-gray-200 hover:bg-gray-50 {{ $task->task_status == 'assigned' ? 'bg-red-50' : '' }}">
                    <td class="p-3">
                        <div class="font-bold">#{{ $task->report->id }} - {{ $task->report->facility_name }}</div>
                        <div class="text-xs text-gray-600 mt-1 truncate w-48">{{ $task->report->description }}</div>
                    </td>
                    <td class="p-3">
                        {{ $task->report->building }}, {{ $task->report->floor }}, {{ $task->report->room }}
                    </td>
                    <td class="p-3 text-center">
                        @if($task->report->sawResult)
                            <span class="font-black text-lg text-telkom-red">Rank {{ $task->report->sawResult->rank_position }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <span class="badge-{{ $task->task_status }}">{{ ucfirst(str_replace('_', ' ', $task->task_status)) }}</span>
                    </td>
                    <td class="p-3">
                        <a href="{{ route('staff.tasks.show', $task) }}" class="neu-btn-primary py-1 px-3 text-sm">Update</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500 font-semibold">Belum ada tugas yang diberikan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Tugas - SiLapor')
@section('header', 'Update Tugas Maintenance')

@section('content')
<div class="mb-4">
    <a href="{{ route('staff.dashboard') }}" class="text-blue-600 font-bold hover:underline">&larr; Kembali ke Daftar Tugas</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Informasi Laporan -->
    <div class="neu-card p-6 space-y-4">
        <h3 class="text-xl font-bold mb-4 border-b-2 border-neu-border pb-2">Informasi Laporan</h3>
        
        <div class="bg-gray-50 p-4 border-2 border-neu-border rounded-lg mb-4">
            <h4 class="font-bold text-lg">{{ $task->report->title }}</h4>
            <p class="text-sm text-gray-600">ID Laporan: #{{ $task->report->id }}</p>
            @if($task->report->sawResult)
                <p class="mt-2"><span class="bg-yellow-200 text-yellow-900 px-2 py-1 rounded font-bold border border-neu-border text-sm shadow-[2px_2px_0px_rgba(0,0,0,1)]">Prioritas SAW: Rank {{ $task->report->sawResult->rank_position }}</span></p>
            @endif
        </div>

        <table class="w-full text-left">
            <tbody>
                <tr class="border-b border-gray-200"><th class="py-2 pr-4 w-1/3">Fasilitas</th><td class="py-2">{{ $task->report->facility_name }}</td></tr>
                <tr class="border-b border-gray-200"><th class="py-2 pr-4">Lokasi</th><td class="py-2">{{ $task->report->building }}, {{ $task->report->floor }}, {{ $task->report->room }}</td></tr>
                <tr><th class="py-2 pr-4 align-top">Deskripsi</th><td class="py-2">{{ $task->report->description }}</td></tr>
            </tbody>
        </table>

        @if($task->report->photo)
            <div class="mt-4">
                <p class="font-bold mb-2">Foto Kerusakan:</p>
                <img src="{{ asset('storage/' . $task->report->photo) }}" alt="Foto Kerusakan" class="w-full h-auto border-2 border-neu-border rounded-lg shadow-[4px_4px_0px_rgba(0,0,0,1)]">
            </div>
        @endif
    </div>

    <!-- Form Update Tugas -->
    <div class="neu-card p-6 bg-blue-50">
        <h3 class="text-xl font-bold mb-4 border-b-2 border-neu-border pb-2 text-blue-900">Update Status Tugas</h3>
        
        <div class="mb-6 bg-white p-4 border-2 border-neu-border rounded-lg">
            <p class="font-bold mb-1">Status Saat Ini:</p>
            <span class="badge-{{ $task->task_status }} text-lg px-4 py-2">{{ ucfirst(str_replace('_', ' ', $task->task_status)) }}</span>
        </div>

        @if($task->task_status !== 'completed')
        <form action="{{ route('staff.tasks.update', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label class="block font-bold mb-1" for="task_status">Ubah Status</label>
                <select name="task_status" id="task_status" required class="neu-input w-full">
                    <option value="in_progress" {{ $task->task_status == 'in_progress' ? 'selected' : '' }}>Dalam Pengerjaan (In Progress)</option>
                    <option value="completed">Selesai Diperbaiki (Completed)</option>
                </select>
            </div>

            <div id="photo_field" style="display: none;">
                <label class="block font-bold mb-1" for="completion_photo">Foto Bukti Perbaikan</label>
                <input type="file" id="completion_photo" name="completion_photo" accept="image/*" class="neu-input w-full bg-white p-1">
                <p class="text-xs text-red-600 mt-1 font-semibold">* Wajib diisi jika status Selesai</p>
            </div>

            <div>
                <label class="block font-bold mb-1" for="staff_note">Catatan Perbaikan</label>
                <textarea name="staff_note" id="staff_note" rows="4" class="neu-input w-full" placeholder="Jelaskan apa saja yang diperbaiki...">{{ $task->staff_note }}</textarea>
            </div>

            <button type="submit" class="neu-btn-primary w-full">Simpan Perubahan</button>
        </form>
        @else
            <div class="bg-green-100 border-2 border-neu-border p-4 rounded-lg shadow-neu mb-4 text-center">
                <p class="font-bold text-green-900">Tugas ini telah selesai dikerjakan.</p>
                <p class="text-sm mt-1">Diselesaikan pada: {{ \Carbon\Carbon::parse($task->completed_at)->format('d M Y H:i') }}</p>
            </div>

            <div class="bg-white p-4 border-2 border-neu-border rounded-lg mb-4">
                <p class="font-bold mb-2">Catatan Perbaikan:</p>
                <p>{{ $task->staff_note ?: '-' }}</p>
            </div>

            @if($task->completion_photo)
                <div>
                    <p class="font-bold mb-2">Foto Bukti Perbaikan:</p>
                    <img src="{{ asset('storage/' . $task->completion_photo) }}" alt="Foto Perbaikan" class="w-full h-auto border-2 border-neu-border rounded-lg shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('task_status');
        const photoField = document.getElementById('photo_field');
        const photoInput = document.getElementById('completion_photo');

        if(statusSelect) {
            function togglePhotoField() {
                if (statusSelect.value === 'completed') {
                    photoField.style.display = 'block';
                    photoInput.setAttribute('required', 'required');
                } else {
                    photoField.style.display = 'none';
                    photoInput.removeAttribute('required');
                }
            }

            statusSelect.addEventListener('change', togglePhotoField);
            togglePhotoField(); // Run on load
        }
    });
</script>
@endpush

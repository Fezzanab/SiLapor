@extends('layouts.app')

@section('title', 'Detail Laporan - SiLapor')
@section('header', 'Detail & Verifikasi Laporan #' . $report->id)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 font-bold hover:underline">&larr; Kembali ke Dashboard</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Detail -->
    <div class="lg:col-span-2 space-y-6">
        <div class="neu-card p-6">
            <div class="flex justify-between items-start mb-6 border-b-2 border-neu-border pb-4">
                <div>
                    <h2 class="text-2xl font-bold">{{ $report->title }}</h2>
                    <p class="text-gray-600 mt-1">Dilaporkan oleh: {{ $report->reporter->name ?? 'Unknown' }} pada {{ $report->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <span class="badge-{{ $report->status }} text-lg px-4 py-2">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4 border-b-2 border-neu-border pb-2 inline-block">Informasi Fasilitas</h3>
                    <table class="w-full text-left">
                        <tbody>
                            <tr class="border-b border-gray-200"><th class="py-2 pr-4 w-1/3">Nama</th><td class="py-2">{{ $report->facility_name }}</td></tr>
                            <tr class="border-b border-gray-200"><th class="py-2 pr-4">Jenis</th><td class="py-2">{{ ucfirst($report->facility_type) }}</td></tr>
                            <tr class="border-b border-gray-200"><th class="py-2 pr-4">Gedung</th><td class="py-2">{{ $report->building }}</td></tr>
                            <tr class="border-b border-gray-200"><th class="py-2 pr-4">Lantai</th><td class="py-2">{{ $report->floor }}</td></tr>
                            <tr><th class="py-2 pr-4">Ruangan</th><td class="py-2">{{ $report->room }}</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-bold mt-8 mb-4 border-b-2 border-neu-border pb-2 inline-block">Skor Laporan</h3>
                    <table class="w-full text-left">
                        <tbody>
                            <tr class="border-b border-gray-200"><th class="py-2 pr-4">Tingkat Keparahan (C1)</th><td class="py-2">{{ $report->severity_score }} / 5</td></tr>
                            <tr class="border-b border-gray-200"><th class="py-2 pr-4">Dampak Akademik (C2)</th><td class="py-2">{{ $report->academic_impact_score }} / 5</td></tr>
                            <tr class="border-b border-gray-200"><th class="py-2 pr-4">Frekuensi (C3)</th><td class="py-2">{{ $report->frequency_score }} / 5</td></tr>
                            <tr><th class="py-2 pr-4">Estimasi Biaya (C4)</th><td class="py-2">{{ $report->estimated_cost_score }} / 5</td></tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4 border-b-2 border-neu-border pb-2 inline-block">Foto Kerusakan</h3>
                    @if($report->photo)
                        <img src="{{ asset('storage/' . $report->photo) }}" alt="Foto Kerusakan" class="w-full h-auto border-2 border-neu-border rounded-lg shadow-[4px_4px_0px_rgba(0,0,0,1)]">
                    @else
                        <div class="bg-gray-100 border-2 border-neu-border border-dashed rounded-lg h-48 flex items-center justify-center text-gray-500 font-bold">
                            Tidak ada foto
                        </div>
                    @endif
                </div>
            </div>
            
            <h3 class="text-lg font-bold mt-8 mb-4 border-b-2 border-neu-border pb-2 inline-block">Deskripsi Laporan</h3>
            <p class="bg-gray-50 p-4 border-2 border-neu-border rounded-lg">{{ $report->description }}</p>
        </div>
    </div>

    <!-- Kolom Kanan: Aksi -->
    <div class="space-y-6">
        <!-- Verifikasi Form -->
        @if($report->status === 'pending')
        <div class="neu-card p-6 border-telkom-red border-4">
            <h3 class="text-xl font-bold mb-4 flex items-center text-telkom-red">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Verifikasi Laporan
            </h3>
            <form action="{{ route('admin.reports.verify', $report) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-bold mb-1" for="status">Ubah Status</label>
                    <select name="status" id="status" required class="neu-input w-full" onchange="toggleScores(this.value)">
                        <option value="">-- Pilih Status --</option>
                        <option value="valid">Valid (Terima)</option>
                        <option value="invalid">Invalid (Tolak)</option>
                        <option value="duplicate">Duplikat</option>
                    </select>
                </div>

                <div id="score-fields" style="display: none;" class="space-y-4 p-4 bg-gray-50 border-2 border-neu-border rounded-lg">
                    <h4 class="font-bold text-sm border-b border-neu-border pb-1">Penilaian Kriteria (1-5)</h4>
                    <div>
                        <label class="block font-bold text-xs mb-1" for="severity_score">C1: Keparahan</label>
                        <input type="number" name="severity_score" id="severity_score" min="1" max="5" class="neu-input w-full text-sm">
                    </div>
                    <div>
                        <label class="block font-bold text-xs mb-1" for="academic_impact_score">C2: Dampak Akademik</label>
                        <input type="number" name="academic_impact_score" id="academic_impact_score" min="1" max="5" class="neu-input w-full text-sm">
                    </div>
                    <div>
                        <label class="block font-bold text-xs mb-1" for="estimated_cost_score">C4: Estimasi Biaya</label>
                        <input type="number" name="estimated_cost_score" id="estimated_cost_score" min="1" max="5" class="neu-input w-full text-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-bold mb-1" for="admin_note">Catatan Admin</label>
                    <textarea name="admin_note" id="admin_note" rows="3" class="neu-input w-full" placeholder="Tambahkan catatan mengapa diterima/ditolak..."></textarea>
                </div>
                <button type="submit" class="neu-btn-primary w-full">Simpan Verifikasi</button>
            </form>
        </div>

        <script>
            function toggleScores(status) {
                const scoreFields = document.getElementById('score-fields');
                const inputs = scoreFields.querySelectorAll('input');
                if (status === 'valid') {
                    scoreFields.style.display = 'block';
                    inputs.forEach(input => input.setAttribute('required', 'required'));
                } else {
                    scoreFields.style.display = 'none';
                    inputs.forEach(input => input.removeAttribute('required'));
                }
            }
        </script>
        @endif

        <!-- Assign Task Form -->
        @if(in_array($report->status, ['valid', 'in_progress']) && $report->sawResult)
        <div class="neu-card p-6">
            <h3 class="text-xl font-bold mb-4">Penugasan Staff</h3>
            
            @if($report->task)
                <div class="bg-blue-50 border-2 border-blue-200 p-4 rounded-lg mb-4">
                    <p class="font-bold">Status Tugas: <span class="badge-{{ $report->task->task_status }}">{{ ucfirst(str_replace('_', ' ', $report->task->task_status)) }}</span></p>
                    <p class="mt-2">Ditugaskan ke: <strong>{{ $report->task->staff->name }}</strong></p>
                </div>
            @endif

            @if(!$report->task || $report->task->task_status === 'assigned')
            <form action="{{ route('admin.reports.assign', $report) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-bold mb-1" for="staff_id">Pilih Staff Maintenance</label>
                    <select name="staff_id" id="staff_id" required class="neu-input w-full">
                        <option value="">-- Pilih Staff --</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ ($report->task && $report->task->staff_id == $staff->id) ? 'selected' : '' }}>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="neu-btn-primary w-full">{{ $report->task ? 'Ubah Penugasan' : 'Tugaskan Staff' }}</button>
            </form>
            @endif
        </div>
        @endif

        @if($report->admin_note)
        <div class="neu-card p-6 bg-yellow-50">
            <h3 class="text-lg font-bold mb-2">Catatan Admin</h3>
            <p>{{ $report->admin_note }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

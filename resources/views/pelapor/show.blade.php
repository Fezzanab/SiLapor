@extends('layouts.app')

@section('title', 'Detail Laporan - SiLapor')
@section('header', 'Detail Laporan #' . $report->id)

@section('content')
<div class="mb-4">
    <a href="{{ route('pelapor.dashboard') }}" class="text-blue-600 font-bold hover:underline">&larr; Kembali ke Dashboard</a>
</div>

<div class="neu-card p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-start mb-6 border-b-2 border-neu-border pb-4">
        <div>
            <h2 class="text-2xl font-bold">{{ $report->title }}</h2>
            <p class="text-gray-600 mt-1">{{ $report->created_at->format('d M Y H:i') }}</p>
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

            <h3 class="text-lg font-bold mt-8 mb-4 border-b-2 border-neu-border pb-2 inline-block">Deskripsi</h3>
            <p class="bg-gray-50 p-4 border-2 border-neu-border rounded-lg">{{ $report->description }}</p>
            
            @if($report->admin_note)
            <h3 class="text-lg font-bold mt-8 mb-4 border-b-2 border-neu-border pb-2 inline-block text-telkom-red">Catatan Admin</h3>
            <p class="bg-yellow-50 p-4 border-2 border-neu-border rounded-lg">{{ $report->admin_note }}</p>
            @endif
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
            
            @if($report->task && $report->task->completion_photo)
            <h3 class="text-lg font-bold mt-8 mb-4 border-b-2 border-neu-border pb-2 inline-block text-green-600">Foto Perbaikan</h3>
            <img src="{{ asset('storage/' . $report->task->completion_photo) }}" alt="Foto Perbaikan" class="w-full h-auto border-2 border-neu-border rounded-lg shadow-[4px_4px_0px_rgba(0,0,0,1)]">
            @endif
        </div>
    </div>
</div>
@endsection

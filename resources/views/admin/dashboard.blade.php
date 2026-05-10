@extends('layouts.app')

@section('title', 'Dashboard Admin - SiLapor')
@section('header', 'Dashboard Administrator')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
    <div class="neu-card p-4 text-center">
        <h3 class="text-sm font-bold text-gray-500 mb-1">Total</h3>
        <p class="text-2xl font-black">{{ $stats['total'] }}</p>
    </div>
    <div class="neu-card p-4 text-center">
        <h3 class="text-sm font-bold text-gray-500 mb-1">Pending</h3>
        <p class="text-2xl font-black text-gray-600">{{ $stats['pending'] }}</p>
    </div>
    <div class="neu-card p-4 text-center">
        <h3 class="text-sm font-bold text-gray-500 mb-1">Valid</h3>
        <p class="text-2xl font-black text-blue-600">{{ $stats['valid'] }}</p>
    </div>
    <div class="neu-card p-4 text-center">
        <h3 class="text-sm font-bold text-gray-500 mb-1">Proses</h3>
        <p class="text-2xl font-black text-yellow-600">{{ $stats['in_progress'] }}</p>
    </div>
    <div class="neu-card p-4 text-center">
        <h3 class="text-sm font-bold text-gray-500 mb-1">Selesai</h3>
        <p class="text-2xl font-black text-green-600">{{ $stats['completed'] }}</p>
    </div>
    <div class="neu-card p-4 text-center">
        <h3 class="text-sm font-bold text-gray-500 mb-1">Invalid</h3>
        <p class="text-2xl font-black text-red-600">{{ $stats['invalid'] }}</p>
    </div>
    <div class="neu-card p-4 text-center">
        <h3 class="text-sm font-bold text-gray-500 mb-1">Duplikat</h3>
        <p class="text-2xl font-black text-purple-600">{{ $stats['duplicate'] }}</p>
    </div>
</div>

<div class="neu-card p-6 mb-8">
    <h3 class="text-xl font-bold mb-6">Akses Cepat</h3>
    <div class="flex gap-4">
        <a href="{{ route('admin.gis') }}" class="neu-btn-primary bg-blue-600 border-neu-border flex-1 text-center">Lihat Peta GIS</a>
        <a href="{{ route('admin.saw.ranking') }}" class="neu-btn-primary bg-yellow-500 border-neu-border text-neu-border flex-1 text-center">Ranking SAW</a>
        <a href="{{ route('admin.audit') }}" class="neu-btn-secondary flex-1 text-center">Audit Trail</a>
    </div>
</div>

<div class="neu-card p-6">
    <h3 class="text-xl font-bold mb-6">Semua Laporan Masuk</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-neu-border">
                    <th class="p-3 font-bold">ID</th>
                    <th class="p-3 font-bold">Fasilitas</th>
                    <th class="p-3 font-bold">Gedung</th>
                    <th class="p-3 font-bold">Tanggal</th>
                    <th class="p-3 font-bold">Status</th>
                    <th class="p-3 font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="p-3">#{{ $report->id }}</td>
                    <td class="p-3 font-semibold">{{ $report->facility_name }}</td>
                    <td class="p-3">{{ $report->building }}, {{ $report->floor }}</td>
                    <td class="p-3">{{ $report->created_at->format('d M Y') }}</td>
                    <td class="p-3">
                        <span class="badge-{{ $report->status }}">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
                    </td>
                    <td class="p-3">
                        <a href="{{ route('admin.reports.show', $report) }}" class="neu-btn-primary py-1 px-3 text-sm">Verifikasi</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500 font-semibold">Belum ada laporan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Ranking Prioritas SAW - SiLapor')
@section('header', 'Ranking Prioritas Perbaikan (SAW)')

@section('content')
<div class="neu-card p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="text-xl font-bold">Hitung Ulang DSS SAW</h3>
            <p class="text-gray-600 text-sm mt-1">Perhitungan ini menggunakan laporan dengan status <span class="badge-valid">Valid</span>.</p>
        </div>
        <form action="{{ route('admin.saw.calculate') }}" method="POST">
            @csrf
            <button type="submit" class="neu-btn-primary flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                Hitung SAW Sekarang
            </button>
        </form>
    </div>
</div>

<div class="neu-card p-6">
    <h3 class="text-xl font-bold mb-6">Hasil Perankingan</h3>

    @if($results->isEmpty())
        <div class="bg-gray-100 p-8 text-center rounded-lg border-2 border-neu-border border-dashed">
            <p class="text-gray-500 font-bold text-lg mb-2">Belum ada hasil perhitungan.</p>
            <p class="text-gray-400">Silakan klik "Hitung SAW Sekarang" untuk memproses data laporan valid.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b-2 border-neu-border">
                        <th class="p-2 font-bold text-center">Rank</th>
                        <th class="p-2 font-bold">Laporan</th>
                        <th class="p-2 font-bold text-center">Skor Akhir</th>
                        <th class="p-2 font-bold text-center bg-gray-50">C1</th>
                        <th class="p-2 font-bold text-center bg-gray-50">C2</th>
                        <th class="p-2 font-bold text-center bg-gray-50">C3</th>
                        <th class="p-2 font-bold text-center bg-gray-50">C4</th>
                        <th class="p-2 font-bold text-center">Status</th>
                        <th class="p-2 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $res)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 {{ $res->rank_position == 1 ? 'bg-yellow-50' : '' }}">
                        <td class="p-2 text-center">
                            @if($res->rank_position == 1)
                                <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full font-black text-lg shadow-[2px_2px_0px_rgba(0,0,0,1)] border border-neu-border">1</span>
                            @elseif($res->rank_position == 2)
                                <span class="bg-gray-300 text-gray-800 px-2 py-1 rounded-full font-black shadow-[2px_2px_0px_rgba(0,0,0,1)] border border-neu-border">2</span>
                            @elseif($res->rank_position == 3)
                                <span class="bg-orange-300 text-orange-900 px-2 py-1 rounded-full font-black shadow-[2px_2px_0px_rgba(0,0,0,1)] border border-neu-border">3</span>
                            @else
                                <span class="font-bold text-gray-500">{{ $res->rank_position }}</span>
                            @endif
                        </td>
                        <td class="p-2">
                            <div class="font-bold">#{{ $res->report->id }} - {{ $res->report->facility_name }}</div>
                            <div class="text-xs text-gray-500">{{ $res->report->building }}, {{ $res->report->room }}</div>
                        </td>
                        <td class="p-2 text-center font-black text-telkom-red text-lg">
                            {{ number_format($res->final_score, 4) }}
                        </td>
                        <!-- Normalized Scores -->
                        <td class="p-2 text-center text-gray-600 bg-gray-50">{{ number_format($res->normalized_c1, 2) }}</td>
                        <td class="p-2 text-center text-gray-600 bg-gray-50">{{ number_format($res->normalized_c2, 2) }}</td>
                        <td class="p-2 text-center text-gray-600 bg-gray-50">{{ number_format($res->normalized_c3, 2) }}</td>
                        <td class="p-2 text-center text-gray-600 bg-gray-50">{{ number_format($res->normalized_c4, 2) }}</td>
                        
                        <td class="p-2 text-center">
                            <span class="badge-{{ $res->report->status }} text-xs">{{ ucfirst(str_replace('_', ' ', $res->report->status)) }}</span>
                        </td>
                        <td class="p-2">
                            <a href="{{ route('admin.reports.show', $res->report) }}" class="neu-btn-primary py-1 px-3 text-xs">Tugaskan</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-xs text-gray-500">
            * Kolom C1-C4 menampilkan nilai yang sudah di-normalisasi (r<sub>ij</sub>).
        </div>
    @endif
</div>
@endsection

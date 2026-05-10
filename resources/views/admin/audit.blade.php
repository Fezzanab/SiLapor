@extends('layouts.app')

@section('title', 'Audit Trail - SiLapor')
@section('header', 'Audit Trail Sistem')

@section('content')
<div class="neu-card p-6">
    <h3 class="text-xl font-bold mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        Riwayat Aktivitas
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-neu-border bg-gray-50">
                    <th class="p-3 font-bold">Waktu</th>
                    <th class="p-3 font-bold">User</th>
                    <th class="p-3 font-bold">Aksi</th>
                    <th class="p-3 font-bold">Laporan</th>
                    <th class="p-3 font-bold">Perubahan Status</th>
                    <th class="p-3 font-bold">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $audit)
                <tr class="border-b border-gray-200 hover:bg-gray-50 text-sm">
                    <td class="p-3 whitespace-nowrap">{{ $audit->created_at->format('d M Y H:i:s') }}</td>
                    <td class="p-3 font-semibold">
                        @if($audit->user)
                            {{ $audit->user->name }}
                            <span class="text-xs text-gray-500 block">({{ $audit->user->role }})</span>
                        @else
                            <span class="text-gray-400 italic">Sistem</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <span class="font-bold uppercase text-xs">{{ $audit->action }}</span>
                    </td>
                    <td class="p-3">
                        <a href="{{ route('admin.reports.show', $audit->report_id) }}" class="text-blue-600 hover:underline font-bold">#{{ $audit->report_id }}</a>
                    </td>
                    <td class="p-3">
                        @if($audit->old_status && $audit->new_status)
                            <span class="text-gray-500">{{ $audit->old_status }}</span> &rarr; <span class="font-bold">{{ $audit->new_status }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="p-3 text-gray-600">
                        {{ $audit->note ?: '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500 font-semibold">Belum ada aktivitas tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

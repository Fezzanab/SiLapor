@extends('layouts.app')

@section('title', 'Buat Laporan - SiLapor')
@section('header', 'Buat Laporan Baru')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<div class="neu-card p-6 max-w-4xl mx-auto">
    <form action="{{ route('pelapor.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold mb-1" for="title">Judul Laporan</label>
                <input type="text" id="title" name="title" required class="neu-input w-full" placeholder="Contoh: AC Bocor di TULT">
            </div>
            
            <div>
                <label class="block font-bold mb-1" for="facility_type">Jenis Fasilitas</label>
                <select id="facility_type" name="facility_type" required class="neu-input w-full">
                    <option value="">Pilih Jenis</option>
                    <option value="AC">AC</option>
                    <option value="kursi">Kursi</option>
                    <option value="meja">Meja</option>
                    <option value="lampu">Lampu</option>
                    <option value="toilet">Toilet</option>
                    <option value="proyektor">Proyektor</option>
                    <option value="wastafel">Wastafel</option>
                    <option value="pintu">Pintu</option>
                    <option value="stop kontak">Stop Kontak</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1" for="facility_name">Nama Fasilitas</label>
                <input type="text" id="facility_name" name="facility_name" required class="neu-input w-full" placeholder="Contoh: AC Panasonic Ruang 201">
            </div>

            <div>
                <label class="block font-bold mb-1" for="building">Gedung</label>
                <input type="text" id="building" name="building" required class="neu-input w-full" placeholder="Contoh: TULT">
            </div>

            <div>
                <label class="block font-bold mb-1" for="floor">Lantai</label>
                <input type="text" id="floor" name="floor" required class="neu-input w-full" placeholder="Contoh: Lantai 2">
            </div>

            <div>
                <label class="block font-bold mb-1" for="room">Ruangan / Area</label>
                <input type="text" id="room" name="room" required class="neu-input w-full" placeholder="Contoh: Ruang 201">
            </div>
        </div>

        <div>
            <label class="block font-bold mb-1" for="description">Deskripsi Kerusakan</label>
            <textarea id="description" name="description" rows="4" required class="neu-input w-full" placeholder="Jelaskan detail kerusakan..."></textarea>
        </div>

        <div>
            <label class="block font-bold mb-1" for="photo">Upload Foto (Opsional)</label>
            <input type="file" id="photo" name="photo" accept="image/*" class="neu-input w-full bg-white p-1">
        </div>



        <div>
            <label class="block font-bold mb-2">Pilih Lokasi pada Peta</label>
            <div id="map" class="h-64 w-full border-2 border-neu-border rounded-lg shadow-[4px_4px_0px_rgba(0,0,0,1)] z-0"></div>
            
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block font-bold text-sm mb-1" for="latitude">Latitude</label>
                    <input type="text" id="latitude" name="latitude" required readonly class="neu-input w-full bg-gray-100">
                </div>
                <div>
                    <label class="block font-bold text-sm mb-1" for="longitude">Longitude</label>
                    <input type="text" id="longitude" name="longitude" required readonly class="neu-input w-full bg-gray-100">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="neu-btn-primary px-8">Kirim Laporan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Initialize map centered at Telkom University
    var map = L.map('map').setView([-6.9740, 107.6303], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    });
</script>
@endpush

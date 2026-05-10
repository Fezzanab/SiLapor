@extends('layouts.app')

@section('title', 'GIS Dashboard - SiLapor')
@section('header', 'Peta Sebaran Kerusakan (GIS)')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .legend {
        background: white;
        padding: 10px;
        border: 2px solid #1a1a1a;
        border-radius: 8px;
        box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
        line-height: 1.5;
    }
    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.8;
        border: 1px solid #000;
        border-radius: 50%;
    }
    .leaflet-popup-content-wrapper {
        border: 2px solid #1a1a1a;
        border-radius: 8px;
        box-shadow: 4px 4px 0px 0px rgba(0,0,0,1);
    }
    .custom-marker {
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        border: 2px solid #1a1a1a;
        color: white;
        font-weight: bold;
        font-size: 10px;
        box-shadow: 2px 2px 0px rgba(0,0,0,1);
    }
</style>
@endpush

@section('content')
<div class="neu-card p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Peta Laporan</h3>
        <div class="flex gap-2">
            <button id="toggle-markers" class="neu-btn-secondary text-sm">Toggle Markers</button>
            <button id="toggle-heatmap" class="neu-btn-secondary text-sm">Toggle Heatmap</button>
        </div>
    </div>
    <div id="map" class="h-[600px] w-full border-2 border-neu-border rounded-lg shadow-[4px_4px_0px_rgba(0,0,0,1)] z-0"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- Leaflet.heat plugin -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
    var map = L.map('map').setView([-6.9740, 107.6303], 15); // Tel-U center
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var reports = @json($reports);
    var markersLayer = L.layerGroup().addTo(map);
    var heatPoints = [];

    // Helper for colors based on status
    function getStatusColor(status) {
        switch(status) {
            case 'pending': return '#9ca3af'; // gray
            case 'valid': return '#3b82f6'; // blue
            case 'in_progress': return '#eab308'; // yellow
            case 'completed': return '#22c55e'; // green
            case 'invalid': return '#ef4444'; // red
            case 'duplicate': return '#a855f7'; // purple
            default: return '#000000';
        }
    }

    reports.forEach(function(report) {
        if (report.latitude && report.longitude) {
            // Add to heatmap
            heatPoints.push([report.latitude, report.longitude, report.severity_score * 0.2]); // weight by severity

            // Custom icon with floor badge
            var color = getStatusColor(report.status);
            var floorBadge = report.floor.replace(/[^0-9]/g, ''); // Extract number
            if (!floorBadge) floorBadge = '-';

            var icon = L.divIcon({
                className: 'custom-icon',
                html: `<div class="custom-marker" style="background-color: ${color}; width: 24px; height: 24px;">L${floorBadge}</div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12],
                popupAnchor: [0, -12]
            });

            var detailUrl = `/admin/reports/${report.id}`;
            var popupContent = `
                <div class="p-2">
                    <strong class="text-lg">#${report.id} - ${report.facility_name}</strong><br>
                    <span class="text-sm text-gray-600">${report.facility_type.toUpperCase()} | ${report.building} | ${report.floor} | ${report.room}</span><br>
                    <div class="mt-2 mb-3">Status: <span style="color: ${color}; font-weight: bold;">${report.status.toUpperCase()}</span></div>
                    <a href="${detailUrl}" class="bg-telkom-red text-white px-3 py-1 rounded text-sm font-bold mt-2" style="text-decoration: none; border: 1px solid #1a1a1a;">Lihat Detail</a>
                </div>
            `;

            L.marker([report.latitude, report.longitude], {icon: icon})
                .bindPopup(popupContent)
                .addTo(markersLayer);
        }
    });

    // Heatmap Layer
    var heatLayer = L.heatLayer(heatPoints, {radius: 25, blur: 15, maxZoom: 17});

    // Toggle logic
    var markersVisible = true;
    var heatVisible = false;

    document.getElementById('toggle-markers').addEventListener('click', function() {
        if(markersVisible) { map.removeLayer(markersLayer); }
        else { map.addLayer(markersLayer); }
        markersVisible = !markersVisible;
    });

    document.getElementById('toggle-heatmap').addEventListener('click', function() {
        if(heatVisible) { map.removeLayer(heatLayer); }
        else { map.addLayer(heatLayer); }
        heatVisible = !heatVisible;
    });

    // Add Legend
    var legend = L.control({position: 'bottomright'});
    legend.onAdd = function (map) {
        var div = L.DomUtil.create('div', 'info legend');
        var statuses = [
            {name: 'Pending', color: '#9ca3af'},
            {name: 'Valid', color: '#3b82f6'},
            {name: 'Proses', color: '#eab308'},
            {name: 'Selesai', color: '#22c55e'},
            {name: 'Invalid', color: '#ef4444'},
            {name: 'Duplikat', color: '#a855f7'}
        ];
        div.innerHTML += '<h4 class="font-bold mb-2">Legenda Status</h4>';
        for (var i = 0; i < statuses.length; i++) {
            div.innerHTML +=
                '<i style="background:' + statuses[i].color + '"></i> ' +
                statuses[i].name + '<br>';
        }
        return div;
    };
    legend.addTo(map);

</script>
@endpush

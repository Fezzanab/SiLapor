<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use App\Models\SawCriterion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Tel-U',
            'email' => 'admin@silapor.test',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        $pelapor1 = User::create([
            'name' => 'Pelapor Satu',
            'email' => 'pelapor@silapor.test',
            'password' => Hash::make('password'),
            'role' => 'pelapor'
        ]);
        $pelapor2 = User::create(['name' => 'Pelapor Dua', 'email' => 'pelapor2@silapor.test', 'password' => Hash::make('password'), 'role' => 'pelapor']);
        $pelapor3 = User::create(['name' => 'Pelapor Tiga', 'email' => 'pelapor3@silapor.test', 'password' => Hash::make('password'), 'role' => 'pelapor']);

        $staff1 = User::create([
            'name' => 'Staff Satu',
            'email' => 'staff@silapor.test',
            'password' => Hash::make('password'),
            'role' => 'staff'
        ]);
        $staff2 = User::create(['name' => 'Staff Dua', 'email' => 'staff2@silapor.test', 'password' => Hash::make('password'), 'role' => 'staff']);
        $staff3 = User::create(['name' => 'Staff Tiga', 'email' => 'staff3@silapor.test', 'password' => Hash::make('password'), 'role' => 'staff']);

        SawCriterion::create(['code' => 'C1', 'name' => 'Tingkat Keparahan Kerusakan', 'type' => 'benefit', 'weight' => 0.35]);
        SawCriterion::create(['code' => 'C2', 'name' => 'Dampak Aktivitas Akademik', 'type' => 'benefit', 'weight' => 0.30]);
        SawCriterion::create(['code' => 'C3', 'name' => 'Frekuensi Laporan', 'type' => 'benefit', 'weight' => 0.20]);
        SawCriterion::create(['code' => 'C4', 'name' => 'Estimasi Biaya Perbaikan', 'type' => 'cost', 'weight' => 0.15]);

        $buildings = ['TULT', 'Gedung Kuliah Umum', 'Gedung Rektorat', 'Gedung P', 'Gedung F'];
        $facilities = ['AC', 'kursi', 'meja', 'lampu', 'toilet', 'proyektor', 'wastafel', 'pintu', 'stop kontak'];
        $statuses = ['pending', 'valid', 'in_progress', 'completed', 'invalid', 'duplicate'];

        for ($i = 1; $i <= 15; $i++) {
            $building = $buildings[array_rand($buildings)];
            $facility = $facilities[array_rand($facilities)];
            
            Report::create([
                'reporter_id' => [$pelapor1->id, $pelapor2->id, $pelapor3->id][array_rand([0, 1, 2])],
                'title' => "Kerusakan $facility di $building",
                'facility_name' => ucfirst($facility) . " Ruang " . rand(100, 500),
                'facility_type' => $facility,
                'building' => $building,
                'floor' => 'Lantai ' . rand(1, 10),
                'room' => 'Ruang ' . rand(101, 505),
                'description' => "Kerusakan pada fasilitas $facility. Harap segera diperbaiki karena mengganggu aktivitas.",
                'latitude' => -6.97 + (rand(-100, 100) / 10000), // Random coord around Tel-U
                'longitude' => 107.63 + (rand(-100, 100) / 10000),
                'severity_score' => rand(1, 5),
                'academic_impact_score' => rand(1, 5),
                'frequency_score' => rand(1, 5),
                'estimated_cost_score' => rand(1, 5),
                'status' => $statuses[array_rand($statuses)]
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Report;
use App\Models\SawCriterion;
use App\Models\SawResult;

class SawController extends Controller
{
    public function criteria()
    {
        $criteria = SawCriterion::all();
        return view('admin.saw.criteria', compact('criteria'));
    }

    public function updateCriterion(Request $request, SawCriterion $criterion)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0|max:1',
        ]);

        $criterion->update(['weight' => $validated['weight']]);

        // Validate total weight is 1.0 (100%)
        $totalWeight = SawCriterion::sum('weight');
        if (abs($totalWeight - 1.0) > 0.01) {
            return back()->with('warning', 'Peringatan: Total bobot saat ini adalah ' . ($totalWeight * 100) . '%. Idealnya total bobot harus 100% (1.0).');
        }

        return back()->with('success', 'Bobot kriteria berhasil diupdate.');
    }

    public function ranking()
    {
        $results = SawResult::with('report')->orderBy('rank_position', 'asc')->get();
        return view('admin.saw.ranking', compact('results'));
    }

    public function calculate()
    {
        $reports = Report::where('status', 'valid')->get();
        
        if ($reports->isEmpty()) {
            return back()->with('error', 'Tidak ada laporan valid untuk dihitung.');
        }

        $criteria = SawCriterion::all()->keyBy('code');

        // Prepare data matrix
        $matrix = [];
        foreach ($reports as $report) {
            // Calculate Frequency (C3) logic based on matching properties
            $frequencyCount = Report::where('status', 'valid')
                ->where('facility_type', $report->facility_type)
                ->where('building', $report->building)
                ->where('floor', $report->floor)
                ->where('room', $report->room)
                ->count();
            
            // Map frequency to 1-5 scale as requested
            $c3_score = 1;
            if ($frequencyCount >= 2 && $frequencyCount <= 3) $c3_score = 2;
            elseif ($frequencyCount >= 4 && $frequencyCount <= 5) $c3_score = 3;
            elseif ($frequencyCount >= 6 && $frequencyCount <= 10) $c3_score = 4;
            elseif ($frequencyCount > 10) $c3_score = 5;

            // Update report frequency_score
            $report->update(['frequency_score' => $c3_score]);

            $matrix[$report->id] = [
                'C1' => $report->severity_score,
                'C2' => $report->academic_impact_score,
                'C3' => $report->frequency_score,
                'C4' => $report->estimated_cost_score,
            ];
        }

        // Find Min/Max
        $maxC1 = max(array_column($matrix, 'C1')) ?: 1;
        $maxC2 = max(array_column($matrix, 'C2')) ?: 1;
        $maxC3 = max(array_column($matrix, 'C3')) ?: 1;
        $minC4 = min(array_column($matrix, 'C4')) ?: 1;

        $resultsData = [];
        foreach ($matrix as $reportId => $scores) {
            // Normalization
            $normC1 = $scores['C1'] / $maxC1; // Benefit
            $normC2 = $scores['C2'] / $maxC2; // Benefit
            $normC3 = $scores['C3'] / $maxC3; // Benefit
            $normC4 = $minC4 / $scores['C4']; // Cost

            // Final Score
            $finalScore = ($normC1 * $criteria['C1']->weight) +
                          ($normC2 * $criteria['C2']->weight) +
                          ($normC3 * $criteria['C3']->weight) +
                          ($normC4 * $criteria['C4']->weight);

            $resultsData[] = [
                'report_id' => $reportId,
                'normalized_c1' => $normC1,
                'normalized_c2' => $normC2,
                'normalized_c3' => $normC3,
                'normalized_c4' => $normC4,
                'final_score' => $finalScore,
            ];
        }

        // Sort to determine rank
        usort($resultsData, function($a, $b) {
            return $b['final_score'] <=> $a['final_score'];
        });

        // Save Results
        SawResult::truncate(); // Clear old results
        
        foreach ($resultsData as $index => $data) {
            $data['rank_position'] = $index + 1;
            $data['calculated_at'] = now();
            $data['created_at'] = now();
            $data['updated_at'] = now();
            SawResult::create($data);
        }

        return back()->with('success', 'Perhitungan SAW berhasil dilakukan.');
    }
}

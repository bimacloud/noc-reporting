<?php

namespace App\Http\Controllers;

use App\Models\CustomerIncident;
use App\Models\DeviceReport;
use App\Models\ServiceLog;
use App\Models\MonthlySummary;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');
        $daysInMonth = $now->daysInMonth;
        $totalMinutesInMonth = $daysInMonth * 24 * 60;

        // 1. Stats for Current Month
        $totalIncidents = CustomerIncident::whereMonth('incident_date', $month)
            ->whereYear('incident_date', $year)
            ->count();

        $totalDowntime = CustomerIncident::whereMonth('incident_date', $month)
            ->whereYear('incident_date', $year)
            ->sum('duration');

        $slaPercentage = $totalMinutesInMonth > 0 
            ? (($totalMinutesInMonth - $totalDowntime) / $totalMinutesInMonth) * 100 
            : 100;

        $totalActivations = ServiceLog::where('type', 'activation')
            ->whereMonth('request_date', $month)
            ->whereYear('request_date', $year)
            ->count();

        // 2. Charts Data
        $incidentFilter = request('incident_filter', 'weekly');
        $incidentQuery = CustomerIncident::query();
        $incidentsChartData = [];

        if ($incidentFilter === 'daily') {
            // Daily this month
            $incidentsChartData = $incidentQuery->whereMonth('incident_date', $month)
                ->whereYear('incident_date', $year)
                ->get()
                ->groupBy(function ($incident) {
                    return Carbon::parse($incident->incident_date)->format('d (D)'); 
                })
                ->map->count()->sortKeys();
        } elseif ($incidentFilter === 'monthly') {
            // Monthly this year
            $incidentsData = $incidentQuery->whereYear('incident_date', $year)
                ->get()
                ->groupBy(function ($incident) {
                    return Carbon::parse($incident->incident_date)->format('M');
                })
                ->map->count();
            // Sort correctly by month order
            $monthsOrder = ['Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12];
            $incidentsChartData = $incidentsData->sortBy(function($val, $key) use ($monthsOrder) {
                return $monthsOrder[$key] ?? 99;
            });
        } elseif ($incidentFilter === 'yearly') {
            // Last 5 years
            $incidentsChartData = $incidentQuery->whereYear('incident_date', '>=', $year - 4)
                ->get()
                ->groupBy(function ($incident) {
                    return Carbon::parse($incident->incident_date)->format('Y');
                })
                ->map->count()->sortKeys();
        } else {
            // Default: Weekly (this month)
            $incidentsChartData = $incidentQuery->whereMonth('incident_date', $month)
                ->whereYear('incident_date', $year)
                ->get()
                ->groupBy(function ($incident) {
                    return 'Week ' . Carbon::parse($incident->incident_date)->format('W');
                })
                ->map->count()->sortKeys();
        }

        // Downtime per Device
        $downtimePerDevice = DeviceReport::with('device')
            ->select('device_id', DB::raw('SUM(duration_downtime) as total_downtime'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('device_id')
            ->orderByDesc('total_downtime')
            ->limit(5)
            ->get()
            ->map(function ($report) {
                return [
                    'device' => $report->device->name ?? 'Unknown',
                    'downtime' => (int) $report->total_downtime
                ];
            });

        // Activations Chart
        $activationFilter = request('activation_filter', 'monthly');
        $activationQuery = ServiceLog::where('type', 'activation');
        $activationsChartData = [];

        // Shared sort order for Monthly views
        $monthsOrder = ['Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12];

        if ($activationFilter === 'daily') {
            // Daily this month
            $activationsChartData = $activationQuery->whereMonth('request_date', $month)
                ->whereYear('request_date', $year)
                ->get()
                ->groupBy(function ($log) {
                    return Carbon::parse($log->request_date)->format('d (D)');
                })
                ->map->count()->sortKeys();
        } elseif ($activationFilter === 'weekly') {
            // Weekly this month
            $activationsChartData = $activationQuery->whereMonth('request_date', $month)
                ->whereYear('request_date', $year)
                ->get()
                ->groupBy(function ($log) {
                    return 'Week ' . Carbon::parse($log->request_date)->format('W');
                })
                ->map->count()->sortKeys();
        } elseif ($activationFilter === 'yearly') {
            // Last 5 years
            $activationsChartData = $activationQuery->whereYear('request_date', '>=', $year - 4)
                ->get()
                ->groupBy(function ($log) {
                    return Carbon::parse($log->request_date)->format('Y');
                })
                ->map->count()->sortKeys();
        } else {
            // Default: Monthly (this year)
            $activationsData = $activationQuery->whereYear('request_date', $year)
                ->get()
                ->groupBy(function ($log) {
                    return Carbon::parse($log->request_date)->format('M');
                })
                ->map->count();
            $activationsChartData = $activationsData->sortBy(function($val, $key) use ($monthsOrder) {
                return $monthsOrder[$key] ?? 99;
            });
        }

        return view('dashboard', compact(
            'totalIncidents',
            'totalDowntime',
            'slaPercentage',
            'totalActivations',
            'incidentsChartData',
            'incidentFilter',
            'downtimePerDevice',
            'activationsChartData',
            'activationFilter'
        ));
    }

    public function exportPdf()
    {
        $month = Carbon::now()->subMonth()->format('Y-m'); // Last month report
        
        $summary = MonthlySummary::where('month', $month)->first();

        if (!$summary) {
            // If no summary exists, generate on the fly or show error
            // For now, let's redirect with error
            return redirect()->back()->with('error', 'No summary available for last month.');
        }

        $pdf = Pdf::loadView('reports.monthly', compact('summary'));
        return $pdf->download('monthly_report_' . $month . '.pdf');
    }
}

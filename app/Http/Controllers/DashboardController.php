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
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::now()->format('Y-m'));
        try {
            $now = Carbon::createFromFormat('Y-m', $selectedDate);
        } catch (\Exception $e) {
            $now = Carbon::now();
            $selectedDate = $now->format('Y-m');
        }

        $month = $now->format('m');
        $year = $now->format('Y');
        $daysInMonth = $now->daysInMonth;
        $totalMinutesInMonth = $daysInMonth * 24 * 60;

        $customerIncidentsCount = CustomerIncident::whereMonth('incident_date', $month)
            ->whereYear('incident_date', $year)
            ->count();

        $backboneIncidentsCount = \App\Models\BackboneIncident::whereMonth('incident_date', $month)
            ->whereYear('incident_date', $year)
            ->count();

        $totalIncidents = $customerIncidentsCount + $backboneIncidentsCount;

        $customerDowntime = CustomerIncident::whereMonth('incident_date', $month)
            ->whereYear('incident_date', $year)
            ->sum('duration');

        $backboneDowntime = \App\Models\BackboneIncident::whereMonth('incident_date', $month)
            ->whereYear('incident_date', $year)
            ->sum('duration');

        $totalDowntime = $customerDowntime + $backboneDowntime;

        // Ensure SLA respects combined downtime
        $slaPercentage = $totalMinutesInMonth > 0 
            ? max(0, (($totalMinutesInMonth - $totalDowntime) / $totalMinutesInMonth) * 100)
            : 100;

        $totalActivations = ServiceLog::where('type', 'activation')
            ->whereMonth('request_date', $month)
            ->whereYear('request_date', $year)
            ->count();

        $totalUpgrades = ServiceLog::where('type', 'upgrade')
            ->whereMonth('request_date', $month)
            ->whereYear('request_date', $year)
            ->count();

        $totalDowngrades = ServiceLog::where('type', 'downgrade')
            ->whereMonth('request_date', $month)
            ->whereYear('request_date', $year)
            ->count();

        // 2. Charts Data
        $incidentFilter = request('incident_filter', 'weekly');
        
        $cQuery = CustomerIncident::query();
        $bQuery = \App\Models\BackboneIncident::query();
        
        $incidentsChartData = [];

        if ($incidentFilter === 'daily') {
            $cData = $cQuery->whereMonth('incident_date', $month)->whereYear('incident_date', $year)->get();
            $bData = $bQuery->whereMonth('incident_date', $month)->whereYear('incident_date', $year)->get();
            
            $merged = $cData->concat($bData)->groupBy(function ($inc) {
                return Carbon::parse($inc->incident_date)->format('d (D)'); 
            })->map->count()->sortKeys();
            
            $incidentsChartData = $merged;
        } elseif ($incidentFilter === 'monthly') {
            $cData = $cQuery->whereYear('incident_date', $year)->get();
            $bData = $bQuery->whereYear('incident_date', $year)->get();
            
            $merged = $cData->concat($bData)->groupBy(function ($inc) {
                return Carbon::parse($inc->incident_date)->format('M');
            })->map->count();
            
            $monthsOrder = ['Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12];
            $incidentsChartData = $merged->sortBy(function($val, $key) use ($monthsOrder) {
                return $monthsOrder[$key] ?? 99;
            });
        } elseif ($incidentFilter === 'yearly') {
            $cData = $cQuery->whereYear('incident_date', '>=', $year - 4)->get();
            $bData = $bQuery->whereYear('incident_date', '>=', $year - 4)->get();
            
            $merged = $cData->concat($bData)->groupBy(function ($inc) {
                return Carbon::parse($inc->incident_date)->format('Y');
            })->map->count()->sortKeys();
            
            $incidentsChartData = $merged;
        } else {
            // Default: Weekly (this month)
            $cData = $cQuery->whereMonth('incident_date', $month)->whereYear('incident_date', $year)->get();
            $bData = $bQuery->whereMonth('incident_date', $month)->whereYear('incident_date', $year)->get();
            
            $merged = $cData->concat($bData)->groupBy(function ($inc) {
                return 'Week ' . Carbon::parse($inc->incident_date)->format('W');
            })->map->count()->sortKeys();
            
            $incidentsChartData = $merged;
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

        // Service Changes Pie Chart
        $activationFilter = request('activation_filter', 'monthly');
        $sQuery = ServiceLog::query();

        // Fetch all relevant data for the selected period
        if ($activationFilter === 'daily') {
            $sQuery->whereMonth('request_date', $month)->whereYear('request_date', $year);
            // Limit to today if daily means today? Or entire month? The previous code used the entire month grouped by day. 
            // If pie chart, we probably just want the total for the selected period. Let's keep it consistent: daily/weekly = this month, monthly = this year, yearly = 5 years.
        } elseif ($activationFilter === 'weekly') {
            $sQuery->whereMonth('request_date', $month)->whereYear('request_date', $year);
        } elseif ($activationFilter === 'yearly') {
            $sQuery->whereYear('request_date', '>=', $year - 4);
        } else { // monthly
            $sQuery->whereYear('request_date', $year);
        }

        $allData = $sQuery->get();

        $pieActivations = $allData->where('type', 'activation')->count();
        $pieUpgrades = $allData->where('type', 'upgrade')->count();
        $pieDowngrades = $allData->where('type', 'downgrade')->count();

        return view('dashboard', compact(
            'totalIncidents',
            'totalDowntime',
            'slaPercentage',
            'totalActivations',
            'totalUpgrades',
            'totalDowngrades',
            'incidentsChartData',
            'incidentFilter',
            'downtimePerDevice',
            'pieActivations',
            'pieUpgrades',
            'pieDowngrades',
            'activationFilter',
            'selectedDate'
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

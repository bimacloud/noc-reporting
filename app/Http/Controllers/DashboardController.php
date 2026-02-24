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
        $selectedMonth = $request->input('month', Carbon::now()->format('m'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));
        
        try {
            $monthNum = $selectedMonth === 'all' ? 1 : $selectedMonth;
            $now = Carbon::createFromDate($selectedYear, $monthNum, 1);
        } catch (\Exception $e) {
            $now = Carbon::now();
            $selectedMonth = $now->format('m');
            $selectedYear = $now->format('Y');
        }

        $month = $selectedMonth; // can be 'all'
        $year = $selectedYear;
        $daysInMonth = $now->daysInMonth;
        $daysInYear = $now->isLeapYear() ? 366 : 365;
        $totalMinutesInPeriod = ($month === 'all' ? $daysInYear : $daysInMonth) * 24 * 60;

        $cQuery = CustomerIncident::whereYear('incident_date', $year);
        if ($month !== 'all') {
            $cQuery->whereMonth('incident_date', $month);
        }
        $customerIncidentsCount = $cQuery->count();

        $bQuery = \App\Models\BackboneIncident::whereYear('incident_date', $year);
        if ($month !== 'all') {
            $bQuery->whereMonth('incident_date', $month);
        }
        $backboneIncidentsCount = $bQuery->count();

        $totalIncidents = $customerIncidentsCount + $backboneIncidentsCount;

        $cDQuery = CustomerIncident::whereYear('incident_date', $year);
        if ($month !== 'all') {
            $cDQuery->whereMonth('incident_date', $month);
        }
        $customerDowntime = $cDQuery->sum('duration');

        $bDQuery = \App\Models\BackboneIncident::whereYear('incident_date', $year);
        if ($month !== 'all') {
            $bDQuery->whereMonth('incident_date', $month);
        }
        $backboneDowntime = $bDQuery->sum('duration');

        $totalDowntime = $customerDowntime + $backboneDowntime;

        // Ensure SLA respects combined downtime
        $slaPercentage = $totalMinutesInPeriod > 0 
            ? max(0, (($totalMinutesInPeriod - $totalDowntime) / $totalMinutesInPeriod) * 100)
            : 100;

        $aQuery = ServiceLog::where('type', 'activation')->whereYear('request_date', $year);
        if ($month !== 'all') {
            $aQuery->whereMonth('request_date', $month);
        }
        $totalActivations = $aQuery->count();

        $uQuery = ServiceLog::where('type', 'upgrade')->whereYear('request_date', $year);
        if ($month !== 'all') {
            $uQuery->whereMonth('request_date', $month);
        }
        $totalUpgrades = $uQuery->count();

        $dQuery = ServiceLog::where('type', 'downgrade')->whereYear('request_date', $year);
        if ($month !== 'all') {
            $dQuery->whereMonth('request_date', $month);
        }
        $totalDowngrades = $dQuery->count();

        // 2. Charts Data
        $incidentFilter = request('incident_filter', 'weekly');
        
        $cQuery = CustomerIncident::query();
        $bQuery = \App\Models\BackboneIncident::query();
        
        $incidentsChartData = [];

        if ($incidentFilter === 'daily') {
            $cQueryDaily = clone $cQuery;
            $bQueryDaily = clone $bQuery;
            
            $cQueryDaily->whereYear('incident_date', $year);
            $bQueryDaily->whereYear('incident_date', $year);
            if ($month !== 'all') {
                $cQueryDaily->whereMonth('incident_date', $month);
                $bQueryDaily->whereMonth('incident_date', $month);
            }
            
            $cData = $cQueryDaily->get();
            $bData = $bQueryDaily->get();
            
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
            // Default: Weekly (this period)
            $cQueryWeekly = clone $cQuery;
            $bQueryWeekly = clone $bQuery;
            
            $cQueryWeekly->whereYear('incident_date', $year);
            $bQueryWeekly->whereYear('incident_date', $year);
            if ($month !== 'all') {
                $cQueryWeekly->whereMonth('incident_date', $month);
                $bQueryWeekly->whereMonth('incident_date', $month);
            }
            
            $cData = $cQueryWeekly->get();
            $bData = $bQueryWeekly->get();
            
            $merged = $cData->concat($bData)->groupBy(function ($inc) {
                return 'Week ' . Carbon::parse($inc->incident_date)->format('W');
            })->map->count()->sortKeys();
            
            $incidentsChartData = $merged;
        }

        // Downtime per Device
        $dtQuery = DeviceReport::with('device')
            ->select('device_id', DB::raw('SUM(duration_downtime) as total_downtime'))
            ->whereYear('created_at', $year)
            ->groupBy('device_id')
            ->orderByDesc('total_downtime')
            ->limit(5);
            
        if ($month !== 'all') {
            $dtQuery->whereMonth('created_at', $month);
        }

        $downtimePerDevice = $dtQuery->get()
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
            $sQuery->whereYear('request_date', $year);
            if ($month !== 'all') $sQuery->whereMonth('request_date', $month);
            // Limit to today if daily means today? Or entire month? The previous code used the entire month grouped by day. 
            // If pie chart, we probably just want the total for the selected period. Let's keep it consistent: daily/weekly = this month, monthly = this year, yearly = 5 years.
        } elseif ($activationFilter === 'weekly') {
            $sQuery->whereYear('request_date', $year);
            if ($month !== 'all') $sQuery->whereMonth('request_date', $month);
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
            'selectedMonth',
            'selectedYear'
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

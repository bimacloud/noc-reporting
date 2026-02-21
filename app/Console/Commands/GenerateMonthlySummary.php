<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonthlySummary;
use App\Models\CustomerIncident;
use App\Models\ServiceLog;
use Carbon\Carbon;

class GenerateMonthlySummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly summary for the previous month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $previousMonth = $now->subMonth();
        $monthStr = $previousMonth->format('m');
        $yearStr = $previousMonth->format('Y');
        $monthKey = $previousMonth->format('Y-m');

        if (MonthlySummary::where('month', $monthKey)->exists()) {
            $this->info("Summary for {$monthKey} already exists.");
            return;
        }

        $daysInMonth = $previousMonth->daysInMonth;
        $totalMinutesInMonth = $daysInMonth * 24 * 60;

        $totalIncidents = CustomerIncident::whereMonth('incident_date', $monthStr)
            ->whereYear('incident_date', $yearStr)
            ->count();

        $totalDowntime = CustomerIncident::whereMonth('incident_date', $monthStr)
            ->whereYear('incident_date', $yearStr)
            ->sum('duration');

        $slaPercentage = $totalMinutesInMonth > 0 
            ? (($totalMinutesInMonth - $totalDowntime) / $totalMinutesInMonth) * 100 
            : 100;

        $totalActivations = ServiceLog::where('type', 'activation')
            ->whereMonth('request_date', $monthStr)
            ->whereYear('request_date', $yearStr)
            ->count();

        $totalUpgrades = ServiceLog::where('type', 'upgrade')
            ->whereMonth('request_date', $monthStr)
            ->whereYear('request_date', $yearStr)
            ->count();

        MonthlySummary::create([
            'month' => $monthKey,
            'total_incident' => $totalIncidents,
            'total_downtime' => $totalDowntime,
            'sla_percentage' => $slaPercentage,
            'total_activation' => $totalActivations,
            'total_upgrade' => $totalUpgrades,
        ]);

        $this->info("Summary for {$monthKey} generated successfully.");
    }
}

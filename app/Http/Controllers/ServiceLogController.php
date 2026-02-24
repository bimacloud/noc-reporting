<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\Customer;
use App\Http\Requests\StoreServiceLogRequest;
use App\Http\Requests\UpdateServiceLogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceLogController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceLog::with('customer');

        if ($request->filled('year')) {
            $query->whereYear('request_date', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('request_date', date('m', strtotime($request->month)))
                  ->whereYear('request_date', date('Y', strtotime($request->month)));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $logs = $query->latest('request_date')->get();
        $customers = Customer::all();

        return view('service_logs.index', compact('logs', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('service_logs.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceLogRequest $request)
    {
        DB::transaction(function () use ($request) {
            $log = ServiceLog::create($request->validated());

            // Auto-update customer bandwidth if upgrade or downgrade
            if (in_array($request->type, ['upgrade', 'downgrade']) && $request->new_bandwidth) {
                $customer = Customer::findOrFail($request->customer_id);
                $customer->update(['bandwidth' => $request->new_bandwidth]);
            }
        });

        return redirect()->route('service_logs.index')
            ->with('success', 'Service log created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceLog $serviceLog)
    {
        return view('service_logs.show', compact('serviceLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceLog $serviceLog)
    {
        $customers = Customer::all();
        return view('service_logs.edit', compact('serviceLog', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceLogRequest $request, ServiceLog $serviceLog)
    {
        // Not auto-updating bandwidth on edit to prevent accidental changes
        // Only log update
        $serviceLog->update($request->validated());

        return redirect()->route('service_logs.index')
            ->with('success', 'Service log updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceLog $serviceLog)
    {
        $serviceLog->delete();

        return redirect()->route('service_logs.index')
            ->with('success', 'Service log deleted successfully.');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=service_logs_template.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Customer Name', 'Type', 'Old Bandwidth', 'New Bandwidth', 'Request Date (YYYY-MM-DD)', 'Execute Date (YYYY-MM-DD)', 'Notes'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Add a sample row
            fputcsv($file, ['John Doe', 'upgrade', '50', '100', '2024-01-01', '2024-01-02', 'Historical import sample.']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:4096'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        
        $header = fgetcsv($handle, 1000, ",");
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 7) {
                 continue; // skip malformed row
            }
            if (empty(trim($data[0]))) {
                 continue; // Customer Name is required
            }

            $customerName = trim($data[0]);
            
            // Look up customer by exact string match
            $customer = Customer::where('name', $customerName)->first();
            
            if (!$customer) {
                $errorCount++;
                $errors[] = "Customer Name: '{$customerName}' - Error: Customer not found.";
                continue;
            }

            $typeRaw = strtolower(trim($data[1]));
            $validTypes = ['activation', 'upgrade', 'downgrade', 'suspension', 'termination'];
            $type = in_array($typeRaw, $validTypes) ? $typeRaw : 'activation';

            $reqDate = isset($data[4]) && !empty(trim($data[4])) ? trim($data[4]) : null;
            $execDate = isset($data[5]) && !empty(trim($data[5])) ? trim($data[5]) : null;

            $parseIdDate = function($dateStr) {
                if (!$dateStr) return null;
                $months = [
                    'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
                    'mei' => 'May', 'juni' => 'June', 'juli' => 'July', 'agustus' => 'August',
                    'oktober' => 'October', 'nopember' => 'November', 'november' => 'November', 'desember' => 'December'
                ];
                $dateStr = str_ireplace(array_keys($months), array_values($months), strtolower($dateStr));
                return \Carbon\Carbon::parse($dateStr)->format('Y-m-d H:i:s');
            };

            try {
                if ($reqDate) {
                    $reqDate = $parseIdDate($reqDate);
                }
                if ($execDate) {
                    $execDate = $parseIdDate($execDate);
                }
                
                // Create explicitly WITHOUT touching Customer bandwidth
                ServiceLog::create([
                    'customer_id' => $customer->id,
                    'type' => $type,
                    'old_bandwidth' => trim($data[2]),
                    'new_bandwidth' => trim($data[3]),
                    'request_date' => $reqDate,
                    'execute_date' => $execDate,
                    'notes' => trim($data[6]),
                ]);
                
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Row for '{$customerName}' failed - Error: {$e->getMessage()}";
            }
        }
        fclose($handle);

        $msg = "Import complete. $successCount service log(s) imported.";
        if ($errorCount > 0) {
            $msg .= " $errorCount error(s) skipped.";
            return redirect()->route('service_logs.index')->with('success', $msg)->with('import_errors', $errors);
        }

        return redirect()->route('service_logs.index')->with('success', $msg);
    }
}

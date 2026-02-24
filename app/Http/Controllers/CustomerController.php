<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\ServiceType;
use App\Models\Provider;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $customers = Customer::with(['serviceType', 'provider'])->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $types = ServiceType::all();
        $providers = Provider::all();
        return view('customers.create', compact('types', 'providers'));
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $serviceLogs = $customer->serviceLogs()->orderBy('request_date', 'desc')->get();
        $incidents = $customer->customerIncidents()->orderBy('incident_date', 'desc')->get();

        return view('customers.show', compact('customer', 'serviceLogs', 'incidents'));
    }

    public function edit(Customer $customer)
    {
        $types = ServiceType::all();
        $providers = Provider::all();
        return view('customers.edit', compact('customer', 'types', 'providers'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=customers_template.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Name', 'Service Type', 'Bandwidth', 'Address', 'Status', 'Registration Date (YYYY-MM-DD)'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Add a sample row
            fputcsv($file, ['John Doe', 'FTTH', '50', 'Jl. Sudirman No 1', 'active', '2024-01-01']);
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
            if (count($data) < 5) continue;
            if (empty(trim($data[0]))) continue;

            $name = trim($data[0]);
            $serviceType = \App\Models\ServiceType::firstOrCreate(['name' => trim($data[1])]);
            $statusRaw = strtolower(trim($data[4]));
            $status = in_array($statusRaw, ['active','inactive','suspended']) ? $statusRaw : 'active';
            $regDate = isset($data[5]) && !empty(trim($data[5])) ? trim($data[5]) : null;

            $parseIdDate = function($dateStr) {
                if (!$dateStr) return null;
                $months = [
                    'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
                    'mei' => 'May', 'juni' => 'June', 'juli' => 'July', 'agustus' => 'August',
                    'oktober' => 'October', 'nopember' => 'November', 'november' => 'November', 'desember' => 'December'
                ];
                $dateStr = str_ireplace(array_keys($months), array_values($months), strtolower($dateStr));
                return \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
            };

            try {
                if ($regDate) {
                    $regDate = $parseIdDate($regDate);
                }
                
                Customer::create([
                    'name' => $name,
                    'service_type_id' => $serviceType->id,
                    'bandwidth' => trim($data[2]),
                    'address' => trim($data[3]),
                    'status' => $status,
                    'registration_date' => $regDate,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Name: {$name} - Error: {$e->getMessage()}";
            }
        }
        fclose($handle);

        $msg = "Import complete. $successCount customer(s) imported.";
        if ($errorCount > 0) {
            $msg .= " $errorCount error(s) skipped.";
            return redirect()->route('customers.index')->with('success', $msg)->with('import_errors', $errors);
        }

        return redirect()->route('customers.index')->with('success', $msg);
    }
}

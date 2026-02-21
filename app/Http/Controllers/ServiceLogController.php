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

        if ($request->filled('month')) {
            $query->whereMonth('request_date', date('m', strtotime($request->month)))
                  ->whereYear('request_date', date('Y', strtotime($request->month)));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $logs = $query->latest('request_date')->paginate(20)->withQueryString();
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
}

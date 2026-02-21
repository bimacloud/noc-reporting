<?php

namespace App\Http\Controllers;

use App\Models\CustomerIncident;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerIncidentRequest;
use App\Http\Requests\UpdateCustomerIncidentRequest;
use Illuminate\Http\Request;

class CustomerIncidentController extends Controller
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
        $query = CustomerIncident::with('customer');

        if ($request->filled('month')) {
            $query->whereMonth('incident_date', date('m', strtotime($request->month)))
                  ->whereYear('incident_date', date('Y', strtotime($request->month)));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Calculate total downtime before pagination
        $totalDowntime = $query->sum('duration');

        $incidents = $query->latest('incident_date')->paginate(20)->withQueryString();
        $customers = Customer::all();

        return view('customer_incidents.index', compact('incidents', 'customers', 'totalDowntime'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('customer_incidents.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerIncidentRequest $request)
    {
        CustomerIncident::create($request->validated());

        return redirect()->route('customer_incidents.index')
            ->with('success', 'Incident logged successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerIncident $customerIncident)
    {
        return view('customer_incidents.show', ['incident' => $customerIncident]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerIncident $customerIncident)
    {
        $customers = Customer::all();
        return view('customer_incidents.edit', compact('customerIncident', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerIncidentRequest $request, CustomerIncident $customerIncident)
    {
        $customerIncident->update($request->validated());

        return redirect()->route('customer_incidents.index')
            ->with('success', 'Incident updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerIncident $customerIncident)
    {
        $customerIncident->delete();

        return redirect()->route('customer_incidents.index')
            ->with('success', 'Incident deleted successfully.');
    }
}

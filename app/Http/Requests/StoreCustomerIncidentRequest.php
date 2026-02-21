<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'incident_date' => 'required|date',
            'physical_issue' => 'boolean',
            'backbone_issue' => 'boolean',
            'layer_issue' => 'nullable|string',
            'duration' => 'required|integer|min:0',
            'root_cause' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'notes' => 'nullable|string',
        ];
    }
}

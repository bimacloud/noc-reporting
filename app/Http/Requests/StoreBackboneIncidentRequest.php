<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBackboneIncidentRequest extends FormRequest
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
            'backbone_link_id' => 'required|exists:backbone_links,id',
            'incident_date' => 'required|date',
            'latency' => 'nullable|string',
            'down_status' => 'required|boolean',
            'duration' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}

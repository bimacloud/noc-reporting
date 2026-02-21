<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpstreamReportRequest extends FormRequest
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
            'upstream_id' => 'required|exists:upstreams,id',
            'month' => 'required|date',
            'status_l1' => 'required|string',
            'status_l2' => 'required|string',
            'status_l3' => 'required|string',
            'advertise' => 'required|string',
            'duration' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}

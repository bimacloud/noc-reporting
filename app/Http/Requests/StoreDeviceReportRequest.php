<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceReportRequest extends FormRequest
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
            'device_id' => 'required|exists:devices,id',
            'month' => 'required|date',
            'physical_status' => 'required|string',
            'psu_status' => 'required|string',
            'fan_status' => 'required|string',
            'layer2_status' => 'required|string',
            'layer3_status' => 'required|string',
            'cpu_status' => 'required|string',
            'throughput_in' => 'required|string',
            'throughput_out' => 'required|string',
            'duration_downtime' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}

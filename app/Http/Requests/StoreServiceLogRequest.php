<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceLogRequest extends FormRequest
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
            'type' => 'required|in:activation,deactivation,upgrade,downgrade,suspension,termination',
            'old_bandwidth' => 'nullable|string',
            'new_bandwidth' => 'nullable|string',
            'request_date' => 'required|date',
            'execute_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}

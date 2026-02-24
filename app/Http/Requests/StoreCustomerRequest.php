<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_type_id' => 'required|exists:service_types,id',
            'provider_id' => 'nullable|exists:providers,id',
            'bandwidth'       => 'nullable|integer|min:1',
            'registration_date'=> 'nullable|date',
            'address'         => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ];
    }
}

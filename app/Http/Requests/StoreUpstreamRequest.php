<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpstreamRequest extends FormRequest
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
            'peer_name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'asn' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'location_id' => 'required|exists:locations,id',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBackboneLinkRequest extends FormRequest
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
            'node_a' => 'required|string|max:255',
            'node_b' => 'required|string|max:255',
            'node_c' => 'nullable|string|max:255',
            'node_d' => 'nullable|string|max:255',
            'node_e' => 'nullable|string|max:255',
            'provider' => 'nullable|string|max:255',
            'media' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
        ];
    }
}

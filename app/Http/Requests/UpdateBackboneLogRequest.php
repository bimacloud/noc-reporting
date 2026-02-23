<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBackboneLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'backbone_link_id' => 'required|exists:backbone_links,id',
            'type' => 'required|in:activation,deactivation,upgrade,downgrade',
            'old_capacity' => 'nullable|string|max:255',
            'new_capacity' => 'nullable|string|max:255',
            'request_date' => 'required|date',
            'execute_date' => 'nullable|date|after_or_equal:request_date',
            'notes' => 'nullable|string'
        ];
    }
}

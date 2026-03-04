<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NearbySearchRequest extends FormRequest
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
            'query' => 'required|string|min:2',
            'radius' => 'nullable|numeric|max:50',
        ];
    }
}

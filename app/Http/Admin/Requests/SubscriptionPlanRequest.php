<?php

namespace App\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'publications_available' => 'required|integer',
            'active' => 'required',
        ];
    }
}

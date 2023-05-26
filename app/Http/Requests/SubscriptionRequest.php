<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Illuminate\Contracts\Validation\ValidationRule;

class SubscriptionRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'publications_available' => 'required|integer',
            'status' => 'required|in:'.implode(',', Subscription::STATUSES),
        ];
    }
}

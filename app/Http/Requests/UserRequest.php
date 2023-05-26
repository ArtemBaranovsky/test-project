<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'password' => 'required|min:8',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
        ];
    }
}

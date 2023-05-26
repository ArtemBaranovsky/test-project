<?php

namespace App\Http\Requests;

use App\Models\Publication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'status' => ['required', Rule::in(Publication::STATUSES)],
            'user_id' => Rule::exists('users', 'id')
        ];
    }
}

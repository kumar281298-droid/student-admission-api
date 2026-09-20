<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'remarks' => 'nullable|string|max:500',
            'reason' => 'required_if:action,reject|nullable|string|max:500',
        ];
    }
}

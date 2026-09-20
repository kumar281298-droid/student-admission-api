<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'college_id' => 'required|exists:colleges,id',
            'course_id' => 'required|exists:courses,id',
            'remarks' => 'nullable|string|max:500',
        ];
    }
}

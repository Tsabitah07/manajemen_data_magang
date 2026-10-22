<?php

namespace App\Http\Requests\internApplication;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class storeRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'internship_id' => ['required', 'string', 'exists:internships,id'],
            'student_id' => ['required', 'string', 'exists:students,id'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'cv_required' => ['required', 'boolean'],
            'status' => ['required', 'in:waiting,accepted,rejected,cancelled,completed'],
        ];
    }
}

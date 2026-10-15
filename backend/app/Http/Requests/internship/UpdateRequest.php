<?php

namespace App\Http\Requests\internship;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'documentation' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'campus_unit_id' => ['nullable', 'string', 'exists:campus_units,id'],
            'status' => ['nullable', 'in:waiting,accepted,rejected,cancelled,completed']
        ];
    }
}

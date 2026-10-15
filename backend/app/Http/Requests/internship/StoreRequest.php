<?php

namespace App\Http\Requests\internship;

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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'quota' => ['required', 'integer', 'min:1'],
            'documentation' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'campus_unit_id' => ['required', 'string', 'exists:campus_units,id'],
            'status' => ['required', 'in:waiting,accepted,rejected,cancelled,completed']
        ];
    }
}

<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ];

        if ($this->user()?->role === 'student') {
            return [
                ...$rules,
                'nim' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('students', 'nim')->ignore($this->user()->studentProfile?->id),
                ],
                'major' => ['required', 'string', 'max:255'],
                'gender' => ['nullable', Rule::in(['male', 'female'])],
                'enrollment_year' => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:'.date('Y')],
            ];
        }

        if ($this->user()?->role === 'campus-unit') {
            return [
                ...$rules,
                'unit_name' => [
                    'required',
                    Rule::in([
                        'Lecturer',
                        'Admissions and Enrollment',
                        'Academic Affairs',
                        'Student Affairs',
                        'Research and Development',
                        'Finance and Administration',
                        'Human Resources',
                        'Information Technology',
                        'Library Services',
                        'International Relations',
                        'Alumni Relations',
                    ]),
                ],
                'position' => [
                    'required',
                    Rule::in(['Head of Unit', 'Deputy Head of Unit', 'Coordinator', 'Staff']),
                ],
            ];
        }

        return $rules;
    }
}

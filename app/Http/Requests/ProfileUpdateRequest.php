<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        if ($this->user()->isRole('admin') || $this->user()->isSuperAdmin()) {
            $rules['username'] = ['required', 'string', 'lowercase', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)];
            $rules['department_id'] = ['required', 'exists:departments,id'];
            $rules['role_id'] = ['required', 'exists:roles,id'];
        }

        return $rules;
    }
}

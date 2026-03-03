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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', \Illuminate\Validation\Rule::unique(\App\Models\User::class)->ignore($this->user()->id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'institution' => ['nullable', 'string', 'max:255'],
        ];

        // Hanya validasi sekre jika yang login adalah Relawan
        if ($this->user()->hasRole('Relawan')) {
            $rules['secretariat_id'] = ['required', 'exists:secretariats,id'];
        }

        return $rules;
    }
}

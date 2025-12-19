<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->user()) {
            return [
                'body' => ['required', 'string', 'max:2000'],
            ];
        }

        return [
            'guest_name' => ['required', 'string', 'max:100'],
            'guest_email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'guest_name.required' => 'Please provide your name if you are commenting as a guest.',
            'body.required' => 'Please write a comment.',
        ];
    }
}

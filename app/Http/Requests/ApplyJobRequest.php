<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}
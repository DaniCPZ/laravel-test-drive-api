<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
            ],
            'description' => [
                'sometimes',
            ],
            'label_id' => [
                'sometimes',
            ],
            'status' => [
                'sometimes',
            ],
        ];
    }
}

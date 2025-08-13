<?php

namespace App\Requests\Api\V1\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after:now',
            'status' => ['sometimes', 'required', Rule::in(['new', 'in_progress', 'done'])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

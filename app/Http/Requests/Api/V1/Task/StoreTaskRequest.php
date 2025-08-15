<?php

namespace App\Http\Requests\Api\V1\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'date|after:now', // todo обязательна ли дата выполнения ?
            'status' => ['required', Rule::in(['new', 'in_progress', 'done'])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

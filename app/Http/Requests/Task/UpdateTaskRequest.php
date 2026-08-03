<?php

namespace App\Http\Requests\Task;

use App\Domain\Enums\Task\TaskPriority;
use App\Domain\Enums\Task\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
            'project_id'=>'required|exists:projects,id',
            'title'=>'required|string|max:255',
            'priority'=>['required', Rule::enum(TaskPriority::class)],
            'status'=>['required', Rule::enum(TaskStatus::class)],
            'due_date'=>'required|date'
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'due_date' => ['nullable', 'date'],
            // المشروع يجب أن يكون ملكًا للمستخدم نفسه
            'project_id' => ['nullable', Rule::exists('projects', 'id')->where('user_id', $this->user()->id)],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'العنوان', 'description' => 'الوصف', 'status' => 'الحالة',
            'priority' => 'الأولوية', 'due_date' => 'تاريخ التسليم', 'project_id' => 'المشروع',
        ];
    }
}

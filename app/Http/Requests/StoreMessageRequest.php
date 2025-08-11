<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Настройте в соответствии с вашей логикой авторизации
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:add',
            'status_id' => 'required|exists:statuses,id',
            'email' => 'required|email',
            'age' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Поле действия обязательно.',
            'action.in' => 'Недопустимое действие.',
            'status_id.required' => 'Поле статуса обязательно.',
            'status_id.exists' => 'Выбранный статус не существует.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Настройте в соответствии с вашей логикой авторизации
    }

    public function rules(): array
    {
        return [
            'row_id' => 'required|exists:messages,id',
            'status_id' => 'required|exists:statuses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'row_id.required' => 'Идентификатор записи обязателен.',
            'row_id.exists' => 'Запись не найдена.',
            'status_id.required' => 'Поле статуса обязательно.',
            'status_id.exists' => 'Выбранный статус не существует.',
        ];
    }
}

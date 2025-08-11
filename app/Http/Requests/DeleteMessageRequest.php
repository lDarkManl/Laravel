<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Настройте в соответствии с вашей логикой авторизации
    }

    public function rules(): array
    {
        return [
            'delete_id' => 'required|exists:messages,id',
        ];
    }

    public function messages(): array
    {
        return [
            'delete_id.required' => 'Идентификатор записи обязателен.',
            'delete_id.exists' => 'Запись не найдена.',
        ];
    }
}

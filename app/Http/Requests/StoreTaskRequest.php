<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreTaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required',
            'task' => 'required',
            'descriptions' => 'required',
            'status' => 'required',
            'attachment' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => "Subject field is required!",
            'task.required' => "Task name field is required!",
            'descriptions.required' => "Task Descriptions field is required!",
            'status.required' => "Status field is required!",
            'attachment.required' => "Attachment field is required!",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            $response = response()->json([
                'status' => 'Error',
                'message' => 'Opps! Invalid Request',
                'data' => null,
                'errors' => $validator->errors()
            ], 422);
        }

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}

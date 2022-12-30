<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class UpdateTodoRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize(): bool {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules(): array {
            return [
                "id"          => "required|numeric|exists:todos,id",
                "title"       => "required|max:255",
                "priority"    => "nullable|in:LOW,NORMAL,HIGH",
                "status"      => "nullable|in:TODO,IN-PROGRESS,COMPLETED",
                "startDate"   => "nullable|date",
                "description" => "nullable|max:255",
                'photoUpload' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10000',
            ];
        }

        public function messages(): array {
            return [
                "photoUpload.max" => "Maximum upload file size exceeded. Max (10MB)",
            ];
        }
    }

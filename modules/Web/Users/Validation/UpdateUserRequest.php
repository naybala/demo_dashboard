<?php

namespace BasicDashboard\Web\Users\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->offsetUnset('_token');
        $this->offsetUnset('_method');
    }

    /**
     * Rules For Updating User
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        $id = customDecoder($this->user);
        return [
            "fullname"     => "required|string|max:100",
            "staff_id"     => ["required", "string", Rule::unique('users', 'staff_id')->ignore($id)->whereNull('deleted_at')],
            "email"        => ["nullable", "email", Rule::unique('users', 'email')->ignore($id)->whereNull('deleted_at')],
            "password"     => "nullable|confirmed|min:8",
            "status"       => "required",
            "role"         => "nullable|string",
            "user_type"    => "required|integer",
            "gender"       => "nullable|integer",
            "dob"          => "nullable|date",
            "phone_number" => "nullable|string|max:50",
            "avatar"       => "nullable|image|max:2048",
            "profile"      => "nullable|array",
            "spouse"       => "nullable|array",
            "father"       => "nullable|array",
            "mother"       => "nullable|array",
            "class_id"     => "nullable|integer",
        ];
    }

    protected function passedValidation(): void
    {
        $this->offsetUnset('id');
        if ($this->password) {
            $this->merge([
                'password' => Hash::make($this->password),
            ]);
        } else {
            $this->request->remove('password');
        }
    }

    public function messages(): array
    {
        return [
            'name.required'     => __('user.username_validation'),
            'email.required'    => __('user.email_validation'),
            'password.required' => __('user.password_validation'),
            'role_id.required'  => __('user.role_id_validation'),
        ];
    }
}

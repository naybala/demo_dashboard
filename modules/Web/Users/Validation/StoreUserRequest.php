<?php
namespace BasicDashboard\Web\Users\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Summary of StoreUserRequest
 * @property string $password
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->offsetUnset('_token');
    }

    /**
     * Rules To Store User
     * @return array<string|mixed>
     */
    public function rules(): array
    {
        return [
            "fullname"     => "required|string|max:100",
            "staff_id"     => ["required", "string", Rule::unique('users', 'staff_id')->whereNull('deleted_at')],
            "email"        => ["nullable", "email", Rule::unique('users', 'email')->whereNull('deleted_at')],
            "password"     => "required|confirmed|min:8",
            "status"       => "required",
            "role"         => "nullable|string",
            "user_type"    => "required|integer",
            "gender"       => "nullable|integer",
            "dob"          => "nullable|date",
            "phone_number" => "nullable|string|max:50",
            "avatar"       => "nullable|string",
            "profile"      => "nullable|array",
            "spouse"       => "nullable|array",
            "father"       => "nullable|array",
            "mother"       => "nullable|array",
            "class_id"     => "nullable|integer",
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required'      => __('user.username_validation'),
            'email.required'     => __('user.email_validation'),
            'password.required'  => __('user.password_validation'),
            'country_id.numeric' => __('user.country_id_validation'),
            'role_id.numeric'    => __('user.role_id_validation'),
        ];
    }
}

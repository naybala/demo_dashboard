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


    public function getUserData(): array
    {
        $data = $this->validated();
        $profile = $this->input('profile', []);

        return [
            'user_data' => [
                'fullname'     => $data['fullname'],
                'staff_id'     => $data['staff_id'],
                'email'        => $data['email'] ?? null,
                'password'     => Hash::make($data['password']),
                'user_type'    => $data['user_type'],
                'gender'       => $data['gender'] ?? null,
                'dob'          => $data['dob'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'status'       => $data['status'] ?? 'active',
                'avatar'       => $data['avatar'] ?? null,
                'role_marked'  => $data['role'] ?? null,
                'department_name' => $profile['work_experience']['department'] ?? null,
                'position'        => $profile['work_experience']['position'] ?? null,
                'degree'          => $profile['education_background']['degree'] ?? null,
                'certificate'     => $profile['education_background']['certificate'] ?? null,
                'institution'     => $profile['education_background']['institution'] ?? null,
                'year'            => $profile['education_background']['year'] ?? null,
                'specialization'  => $profile['education_background']['specialization'] ?? null,
                'work_years'      => $profile['work_experience']['years'] ?? null,
                'duration'        => $profile['work_experience']['duration'] ?? null,
                'location'        => $profile['work_experience']['location'] ?? null,
                'marital_status'  => $profile['marital_status'] ?? null,
                'place_of_birth'  => $profile['place_of_birth'] ?? null,
                'nrc'             => $profile['nrc'] ?? null,
                'religion'        => $profile['religion'] ?? null,
                'nationality'     => $profile['nationality'] ?? null,
                'professional_subject' => $profile['professional_subject'] ?? null,
                'possessive_grade'     => $profile['possessive_grade'] ?? null,
                'current_address'      => $profile['current_address'] ?? null,
                'permanent_address'    => $profile['permanent_address'] ?? null,
                'professional_qualifications' => $profile['professional_qualifications'] ?? null,
            ],
            'relations' => [
                'spouse'   => $this->input('spouse', []),
                'father'   => $this->input('father', []),
                'mother'   => $this->input('mother', []),
                'role'     => $this->input('role'),
                'class_id' => $this->input('class_id'),
            ]
        ];
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

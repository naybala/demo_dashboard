<?php

namespace BasicDashboard\Web\Users\Services;

use BasicDashboard\Foundations\Domain\Users\User;
use App\Enums\Users\UserType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getPaginatedUsers(array $filters)
    {
        return User::query()
            ->where('user_type', UserType::User)
            ->filterByKeyword($filters['keyword'] ?? null)
            ->with(['roles', 'guardians'])
            ->orderByLatest()
            ->paginate(10);
    }

    public function createUser(array $data)
    {
        return DB::transaction(function () use ($data) {
            $userData = array_merge([
                'fullname'     => $data['fullname'],
                'staff_id'     => $data['staff_id'],
                'email'        => $data['email'] ?? null,
                'password'     => Hash::make($data['password'] ?? 'password'),
                'user_type'    => $data['user_type'],
                'gender'       => $data['gender'] ?? null,
                'dob'          => $data['dob'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'status'          => $data['status'] ?? 'active',
                'avatar'          => $data['avatar'] ?? null,
                'role_marked'     => $data['role'] ?? null,
                'department_name' => $data['profile']['work_experience']['department'] ?? null,
                'position'        => $data['profile']['work_experience']['position'] ?? null,
                'degree'          => $data['profile']['education_background']['degree'] ?? null,
                'certificate'     => $data['profile']['education_background']['certificate'] ?? null,
                'institution'     => $data['profile']['education_background']['institution'] ?? null,
                'year'            => $data['profile']['education_background']['year'] ?? null,
                'specialization'  => $data['profile']['education_background']['specialization'] ?? null,
                'work_years'      => $data['profile']['work_experience']['years'] ?? null,
                'duration'        => $data['profile']['work_experience']['duration'] ?? null,
                'location'        => $data['profile']['work_experience']['location'] ?? null,
            ], $data['profile'] ?? []);

            $user = User::create($userData);

            if (!empty($data['spouse']) && !empty($data['spouse']['name'])) {
                $user->guardians()->create(array_merge($data['spouse'], ['relation' => 'spouse']));
            }

            if (!empty($data['father']) && !empty($data['father']['name'])) {
                $user->guardians()->create(array_merge($data['father'], ['relation' => 'father']));
            }

            if (!empty($data['mother']) && !empty($data['mother']['name'])) {
                $user->guardians()->create(array_merge($data['mother'], ['relation' => 'mother']));
            }

            if (!empty($data['role'])) {
                $user->assignRole($data['role']);
            }

            if (!empty($data['class_id'])) {
                DB::table('classes')->where('id', $data['class_id'])->update(['head_teacher_id' => $user->id]);
            }

            return $user;
        });
    }

    public function updateUser(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $userData = array_merge([
                'fullname'     => $data['fullname'],
                'staff_id'     => $data['staff_id'],
                'email'        => $data['email'] ?? $user->email,
                'user_type'    => $data['user_type'],
                'gender'       => $data['gender'] ?? $user->gender,
                'dob'          => $data['dob'] ?? $user->dob,
                'phone_number'    => $data['phone_number'] ?? $user->phone_number,
                'avatar'          => $data['avatar'] ?? $user->avatar,
                'role_marked'     => $data['role'] ?? $user->role_marked,
                'department_name' => $data['profile']['work_experience']['department'] ?? $user->department_name,
                'position'        => $data['profile']['work_experience']['position'] ?? $user->position,
                'degree'          => $data['profile']['education_background']['degree'] ?? $user->degree,
                'certificate'     => $data['profile']['education_background']['certificate'] ?? $user->certificate,
                'institution'     => $data['profile']['education_background']['institution'] ?? $user->institution,
                'year'            => $data['profile']['education_background']['year'] ?? $user->year,
                'specialization'  => $data['profile']['education_background']['specialization'] ?? $user->specialization,
                'work_years'      => $data['profile']['work_experience']['years'] ?? $user->work_years,
                'duration'        => $data['profile']['work_experience']['duration'] ?? $user->duration,
                'location'        => $data['profile']['work_experience']['location'] ?? $user->location,
            ], $data['profile'] ?? []);

            $user->update($userData);

            if (isset($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            if (isset($data['spouse'])) {
                if (empty($data['spouse']['name'])) {
                    $user->guardians()->where(['relation' => 'spouse'])->delete();
                } else {
                    $user->guardians()->updateOrCreate(
                        ['relation' => 'spouse'],
                        $data['spouse']
                    );
                }
            }

            if (isset($data['father'])) {
                if (empty($data['father']['name'])) {
                    $user->guardians()->where(['relation' => 'father'])->delete();
                } else {
                    $user->guardians()->updateOrCreate(
                        ['relation' => 'father'],
                        $data['father']
                    );
                }
            }

            if (isset($data['mother'])) {
                if (empty($data['mother']['name'])) {
                    $user->guardians()->where(['relation' => 'mother'])->delete();
                } else {
                    $user->guardians()->updateOrCreate(
                        ['relation' => 'mother'],
                        $data['mother']
                    );
                }
            }

            if (!empty($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            if (array_key_exists('class_id', $data)) {
                DB::table('classes')->where('head_teacher_id', $user->id)->update(['head_teacher_id' => null]);
                if (!empty($data['class_id'])) {
                    DB::table('classes')->where('id', $data['class_id'])->update(['head_teacher_id' => $user->id]);
                }
            }

            return $user;
        });
    }

    public function deleteUser(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->guardians()->whereIn('relation', ['spouse', 'father', 'mother'])->delete();
            DB::table('classes')->where('head_teacher_id', $user->id)->update(['head_teacher_id' => null]);
            return $user->delete();
        });
    }
}

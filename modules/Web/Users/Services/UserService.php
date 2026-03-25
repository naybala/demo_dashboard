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
            ->with(['profile', 'roles', 'guardians'])
            ->orderByLatest()
            ->paginate(10);
    }

    public function createUser(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'fullname'     => $data['fullname'],
                'staff_id'     => $data['staff_id'],
                'email'        => $data['email'] ?? null,
                'password'     => Hash::make($data['password'] ?? 'password'),
                'user_type'    => $data['user_type'],
                'gender'       => $data['gender'] ?? null,
                'dob'          => $data['dob'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'status'       => $data['status'] ?? 'active',
            ]);

            $user->profile()->create($data['profile'] ?? []);

            if (!empty($data['spouse']) && !empty($data['spouse']['name'])) {
                $user->guardians()->create(array_merge($data['spouse'], ['relation' => 'spouse']));
            }

            if (!empty($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user;
        });
    }

    public function updateUser(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'fullname'     => $data['fullname'],
                'staff_id'     => $data['staff_id'],
                'email'        => $data['email'] ?? $user->email,
                'user_type'    => $data['user_type'],
                'gender'       => $data['gender'] ?? $user->gender,
                'dob'          => $data['dob'] ?? $user->dob,
                'phone_number' => $data['phone_number'] ?? $user->phone_number,
            ]);

            if (isset($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            $user->profile()->updateOrCreate(['user_id' => $user->id], $data['profile'] ?? []);

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

            if (!empty($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            return $user;
        });
    }

    public function deleteUser(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->profile()?->delete();
            $user->guardians()->where(['relation' => 'spouse'])->delete();
            return $user->delete();
        });
    }
}

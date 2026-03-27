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
            ->filterByKeyword($filters['keyword'] ?? null)
            ->with(['roles', 'guardians'])
            ->orderByLatest()
            ->paginate(10);
    }

    public function createUser(array $data)
    {
        return DB::transaction(function () use ($data) {
            $userData = $data['user_data'];
            
            if (empty($userData['staff_id'])) {
                $userData['staff_id'] = $this->generateStaffId();
            }
            
            $user = User::create($userData);

            if (!empty($data['relations']['spouse']['name'])) {
                $user->guardians()->create(array_merge($data['relations']['spouse'], ['relation' => 'spouse']));
            }

            if (!empty($data['relations']['father']['name'])) {
                $user->guardians()->create(array_merge($data['relations']['father'], ['relation' => 'father']));
            }

            if (!empty($data['relations']['mother']['name'])) {
                $user->guardians()->create(array_merge($data['relations']['mother'], ['relation' => 'mother']));
            }

            if (!empty($data['relations']['role'])) {
                $user->assignRole($data['relations']['role']);
            }

            if (!empty($data['relations']['class_id'])) {
                DB::table('classes')->where('id', $data['relations']['class_id'])->update(['head_teacher_id' => $user->id]);
            }

            return $user;
        });
    }

    public function updateUser(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update($data['user_data']);

            if (isset($data['relations']['spouse'])) {
                if (empty($data['relations']['spouse']['name'])) {
                    $user->guardians()->where(['relation' => 'spouse'])->delete();
                } else {
                    $user->guardians()->updateOrCreate(
                        ['relation' => 'spouse'],
                        $data['relations']['spouse']
                    );
                }
            }

            if (isset($data['relations']['father'])) {
                if (empty($data['relations']['father']['name'])) {
                    $user->guardians()->where(['relation' => 'father'])->delete();
                } else {
                    $user->guardians()->updateOrCreate(
                        ['relation' => 'father'],
                        $data['relations']['father']
                    );
                }
            }

            if (isset($data['relations']['mother'])) {
                if (empty($data['relations']['mother']['name'])) {
                    $user->guardians()->where(['relation' => 'mother'])->delete();
                } else {
                    $user->guardians()->updateOrCreate(
                        ['relation' => 'mother'],
                        $data['relations']['mother']
                    );
                }
            }

            if (!empty($data['relations']['role'])) {
                $user->syncRoles([$data['relations']['role']]);
            }

            if (array_key_exists('class_id', $data['relations'])) {
                DB::table('classes')->where('head_teacher_id', $user->id)->update(['head_teacher_id' => null]);
                if (!empty($data['relations']['class_id'])) {
                    DB::table('classes')->where('id', $data['relations']['class_id'])->update(['head_teacher_id' => $user->id]);
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

    private function generateStaffId(): string
    {
        $prefix = 'STAF-';
        $year = date('Y');
        $fullPrefix = $prefix . $year . '-';
        
        $latest = User::withTrashed()
            ->where('staff_id', 'like', $fullPrefix . '%')
            ->orderBy('staff_id', 'desc')
            ->first();

        if (!$latest) {
            return $fullPrefix . '0001';
        }

        $lastNumber = (int) substr($latest->staff_id, strlen($fullPrefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return $fullPrefix . $nextNumber;
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use BasicDashboard\Foundations\Domain\Users\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $user = User::create($this->addOurNewData());
            $user->assignRole('super_admin');
        }catch (\Exception $e){
            dd($e->getMessage());
            \Log::error($e->getMessage());
        }
    }

   
    private function addOurNewData()
    {
        return [
            'fullname' => 'Korea Bala',
            'email' => 'adminbala@gmail.com',
            'password' => Hash::make('password'),
            'status' => 1,
            'user_type' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

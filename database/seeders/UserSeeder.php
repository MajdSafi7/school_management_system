<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        // $studentRole = Role::where('name', 'student')->first();
        // $teacherRole = Role::where('name', 'teacher')->first();

        $admin = User::create([
            // 'username' => 'admin',
            // 'email' => 'admin@gmail.com',
            'username' => 'Ammar',
            'email' => 'Ammar@gmail.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '0982738724',
            'status' => 'accepted',
            'role_id' => $adminRole->id,
            'email_verification_code' => null,
            'email_verified_at' => now(),
        ]);

        Person::create([
            'user_id' => $admin->id,
            // 'first_name' => 'Admin',
            // 'last_name' => 'User',
            'first_name' => 'Ammar',
            'last_name' => 'shaban',
            'birthdate' => '2000/2/2',
            'personal_photo' => 'test',
            'id_photo' => 'test',
        ]);
    }
}

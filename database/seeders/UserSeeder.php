<?php

namespace Database\Seeders;

use App\Models\Person;
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
        $admin = User::create([
            'username' => 'Ammar',
            'email' => 'Ammar@gmail.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '0982738724',
        ]);

        Person::create([
            'user_id' => $admin->id,
            'first_name' => 'Ammar',
            'last_name' => 'shaban',
            'birthdate' => '2000/2/2',
            'personal_photo' => 'test',
            'id_photo' => 'test',
        ]);
    }
}

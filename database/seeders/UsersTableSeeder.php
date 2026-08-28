<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Laratrust\Models\LaratrustRole;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin user
        $admin = User::firstOrCreate(
            [
                'first_name' => 'Admin',
                'last_name' => 'Book My Water',
                'email' => 'admin@dze.com',
                'phone' => '8085122017',
                'password' => bcrypt('dze@$^*1122'),
                'email_verified_at' => now()
            ]
        );
        $admin->syncRoles([LaratrustRole::where('name', 'admin')->first()]);

        // Regular user
        $user = User::firstOrCreate(
            [
                'first_name' => 'User',
                'last_name' => 'Book My Water',
                'email' => 'user@dze.com',
                'phone' => '0000000000',
                'delegate_access' => '123456',
                'password' => bcrypt('dze@$^*1122'),
                'email_verified_at' => now()
            ]
        );
        $user->syncRoles([LaratrustRole::where('name', 'user')->first()]);

        // Member user
        $member = User::firstOrCreate(
            [
                'first_name' => 'Member',
                'last_name' => 'Book My Water',
                'email' => 'member@dze.com',
                'phone' => '0000000000',
                'delegate_access' => '123456',
                'password' => bcrypt('dze@$^*1122'),
                'email_verified_at' => now()
            ]
        );
        $member->syncRoles([LaratrustRole::where('name', 'member')->first()]);
    }
}

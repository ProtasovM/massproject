<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var User $u */
        $u = User::create([
            'name' => 'dev@dev.dev',
            'email' => 'dev@dev.dev',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('ddev@dev.dev'),
        ]);
        $u->assignRole(Role::MODERATOR_TYPE);

        User::create([
            'name' => 'user@user.user',
            'email' => 'user@user.user',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('user@user.user'),
        ]);

        User::factory()->count(1000)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);

        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@kriembeauty.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        $admin->assignRole('admin');

        $this->call(ProductSeeder::class);
    }
}

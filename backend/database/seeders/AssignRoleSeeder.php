<?php

namespace Database\Seeders;

use App\Repositories\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = UserRepository::query()->where('email', 'admin@readylms.com')->orWhere('email', 'admin@example.com')->first();
        $user->assignRole('admin');
    }
}

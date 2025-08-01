<?php

namespace Database\Seeders;

use App\Repositories\InstructorRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecoverStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = UserRepository::query()->where('email', 'user@readylms.com')->first();
        if ($user) {
            $instructor = InstructorRepository::query()->where('user_id', $user->id)->first();
            if ($instructor) {
                $instructor->delete();
                $instructor->forceDelete();
            }
        }
    }
}

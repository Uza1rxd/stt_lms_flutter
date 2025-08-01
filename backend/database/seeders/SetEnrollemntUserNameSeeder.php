<?php

namespace Database\Seeders;

use App\Repositories\EnrollmentRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetEnrollemntUserNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = EnrollmentRepository::query()
            ->whereNull('certificate_user_name')
            ->with('user')
            ->get();

        foreach ($enrollments as $enrollment) {
            if ($enrollment->user) {
                $enrollment->certificate_user_name = $enrollment->user->name;
                $enrollment->save();
            }
        }
    }
}

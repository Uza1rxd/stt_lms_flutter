<?php

namespace Database\Seeders;

use App\Repositories\PlanRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $standard = [
            'title' => 'Standard',
            'price' => 500,
            'plan_type' => 'monthly',
            'duration' => 30,
            'course_limit' => 2,
            'features' => json_encode([
                'Certificate of Completion',
                'Access to All Quizzes & Assignments',
                'Downloadable Course Materials',
                'Priority Support from Instructors',
                'Exclusive Live Sessions & Q&A',
                'Resume & Portfolio Review',
            ]),
            'description' => 'Perfect for beginners who want to get started.',
            'is_active' => true,
            'created_at' => now(),
        ];
        $premium = [
            'title' => 'Premium',
            'price' => 5000,
            'plan_type' => 'monthly',
            'duration' => 90,
            'course_limit' => 5,
            'features' => json_encode([
                'Certificate of Completion',
                'Access to All Quizzes & Assignments',
                'Downloadable Course Materials',
                'Priority Support from Instructors',
                'Exclusive Live Sessions & Q&A',
                'Resume & Portfolio Review',
            ]),
            'description' => 'Unlock more content and support as you grow.',
            'is_active' => true,
            'created_at' => now(),
        ];
        $enterprise = [
            'title' => 'Enterprise',
            'price' => 15000,
            'plan_type' => 'yearly',
            'duration' => 1,
            'course_limit' => 8,
            'features' => json_encode([
                'Certificate of Completion',
                'Access to All Quizzes & Assignments',
                'Downloadable Course Materials',
                'Priority Support from Instructors',
                'Exclusive Live Sessions & Q&A',
                'Resume & Portfolio Review',
            ]),
            'description' => 'Built for teams, organizations, and power users.',
            'is_active' => true,
            'created_at' => now(),
        ];


        PlanRepository::query()->updateOrCreate($standard);
        PlanRepository::query()->updateOrCreate($premium);
        PlanRepository::query()->updateOrCreate($enterprise);
    }
}

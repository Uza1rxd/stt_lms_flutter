<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Plan;
use Illuminate\Support\Str;

class PlanRepository extends Repository
{
    public static function model()
    {
        return Plan::class;
    }

    public static function storeByRequest($request)
    {
        $is_active = false;

        if (isset($request->is_active)) {
            $is_active = true;
        }

        $plan = self::create([
            'title' => Str::title($request->title),
            'plan_type' => $request->plan_type,
            'duration' => $request->duration,
            'price' => $request->price,
            'course_limit' => $request->course_limit,
            'features' => json_encode($request->features),
            'description' => $request->description,
            'is_active' => $is_active,
            'created_at' => now(),
        ]);

        $plan->courses()->sync($request->course_ids);

        return $plan;
    }

    public static function updateByRequest($request, $plan)
    {
        $is_active = false;

        if (isset($request->is_active)) {
            $is_active = true;
        }

        $plan->update([
            'title' => Str::title($request->title),
            'plan_type' => $request->plan_type,
            'duration' => $request->duration,
            'price' => $request->price,
            'course_limit' => $request->course_limit,
            'features' => json_encode($request->features),
            'description' => $request->description,
            'is_active' => $is_active,
            'created_at' => now(),
        ]);

        $plan->courses()->sync($request->course_ids);

        return $plan;
    }
}

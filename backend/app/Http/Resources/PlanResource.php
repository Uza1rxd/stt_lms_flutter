<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'plan_type' => $this->plan_type,
            'price' => $this->price,
            'duration' => $this->duration,
            'course_limit' => $this->course_limit,
            'description' => $this->description,
            'features' => json_decode($this->features),
            'is_active' => $this->is_active,
            'courses' => PlanCourseResource::collection($this->courses),
        ];
    }
}

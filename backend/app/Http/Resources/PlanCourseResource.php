<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanCourseResource extends JsonResource
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
            'thumbnail' => $this->mediaPath,
            'price' => $this->price,
            'regular_price' => $this->regular_price,
            'instructor' => PlanCourseInstructorResouce::make($this->instructor),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $subscribeAt = Carbon::parse($this->subscribed_at)->format('d M Y');
        $startAt = Carbon::parse($this->starts_at)->format('d M Y');
        $endAt = Carbon::parse($this->ends_at)->format('d M Y');

        $status = $this->ends_at >= now() ? true : false;

        return [
            'id' => $this->id,
            'plan' => $this->plan,
            'transaction_id' => $this->transaction_id,
            'subscribed_at' => $subscribeAt,
            'starts_at' => $startAt,
            'ends_at' => $endAt,
            'status' => $status,
            'course_ids' => $this->courses->pluck('id'),
        ];
    }
}

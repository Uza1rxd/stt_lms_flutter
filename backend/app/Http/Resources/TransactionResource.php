<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $createDate = Carbon::parse($this->created_at)->format('M d, Y (h:i a)');
        return [
            'id' => $this->id,
            'enrollment_id' => $this->enrollment_id,
            'name' => $this->user->name,
            'course_title' => $this->course_title,
            'payment_method' => $this->payment_method,
            'payment_amount' => $this->payment_amount,
            'is_paid' => $this->is_paid,
            'paid_at' => $this->paid_at,
            'status' => 'Paid',
            'pay_at' => $createDate,
        ];
    }
}

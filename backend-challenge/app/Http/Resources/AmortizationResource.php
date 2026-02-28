<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmortizationResource extends JsonResource
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
            'promoter_id' => $this->promoter_id,
            'project_id' => $this->project_id,
            'amount' => $this->amount,
            'state' => $this->state,
            'schedule_date' => $this->schedule_date?->format('Y-m-d'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'project_name' => $this->whenLoaded('project', fn () => $this->project->name),
            'project_wallet_balance' => $this->whenLoaded('project', fn () => $this->project->wallet_balance),
        ];
    }
}

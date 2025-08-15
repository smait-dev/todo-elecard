<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'username' => $this->username ?? '',
            'registered' => $this->created_at?->format('Y-m-d H:i:s') ?? '',
//            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}

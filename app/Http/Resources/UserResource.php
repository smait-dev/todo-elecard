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
            'registered' => $this->created_at?->addHours(7)->format('H:i:s d.m.Y') ?? '', // не лучшее решение
//            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}

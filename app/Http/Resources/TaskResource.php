<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'title' => $this->title ?? '',
            'description' => $this->description ?? '',
            'due_date' => $this->due_date?->format('Y-m-d') ?? '',
            'status' => $this->status ?? '',
            'created_at' => $this->created_at?->addHours(7)->format('H:i:s d.m.Y') ?? '', // не лучшее решение
//            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}

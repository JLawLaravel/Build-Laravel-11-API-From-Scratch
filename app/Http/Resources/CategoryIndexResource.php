<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => str($this->description)->limit(20),
            // 'description' => $this->when($request->is('api/categories*'), function () use ($request) {
            //     if ($request->is('api/categories')) {
            //         return str($this->description)->limit(20);
            //     }
            //     return $this->description;
            // }),
            
        ];
    }
}
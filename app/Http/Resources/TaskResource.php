<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'project'=>new ProjectResource($this->whenLoaded('project')),
            'title'=>$this->title,
            'priority'=>$this->priority,
            'status'=>$this->status,
            'due_date'=>$this->due_date->toDateTimeString(),
        ];
    }
}

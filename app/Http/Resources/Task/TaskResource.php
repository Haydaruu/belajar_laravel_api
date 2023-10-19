<?php

namespace App\Http\Resources\Task;

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
            'title' => $this->title,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'upload_file' => $this->upload_file,
            'created_at' => $this->created_at->translatedFormat('d F Y'),
            'updated_at' => $this->updated_at->translatedFormat('d F Y'),
        ];
    }
}

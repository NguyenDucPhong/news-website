<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image_url' => $this->featured_image_url,

            'status' => $this->status->getLabel(),
            'published_at' => $this->published_at,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    protected function getPostStatusText(): string
    {
        return match ($this->status) {
            0 => 'Nháp',
            1 => 'Chờ duyệt',
            2 => 'Đã đăng',
            default => 'Không xác định',
        };
    }
}

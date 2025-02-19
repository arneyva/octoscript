<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class PostinganResource extends JsonResource
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
            'post_title' => $this->post_title,
            'brand' => $this->brand,
            'platform' => $this->platform,
            'due_date' => $this->due_date,
            'payment' => $this->payment,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    public static function paginate(LengthAwarePaginator $paginate)
    {
        return [
            'list' => self::collection($paginate->getCollection()),
            'limit' => $paginate->perPage(),
            'page' => $paginate->currentPage(),
            'total' => $paginate->total(),
        ];
    }
}

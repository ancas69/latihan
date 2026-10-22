<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //kirim semua data tabel ==> return parent::toArray($request);

        //hanya kirim data (id, nama, email, created_at)
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'dibuat' => $this->created_at->format('Y-m-d H:i:s')
            ];
    }
}

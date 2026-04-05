<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categoryid' => $this->categoryid,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'price' => (float) $this->price,
            'saleprice' => $this->saleprice ? (float) $this->saleprice : null,
            'IsOnSale' => (bool) $this->IsOnSale,
            'IsPublished' => (bool) $this->IsPublished,
            'imageUrl' => $this->imageUrl,
            'cloudinary_public_id' => $this->cloudinary_public_id,
            'inventory' => new InventoryResource($this->whenLoaded('inventory')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Get full image URL based on environment.
     * - Cloudinary: return only full URLs; placeholders for local paths.
     * - Local: return full URLs for local relative paths or file names.
     */
    private function getFullImageUrl($imageUrl)
    {
        if (empty($imageUrl)) {
            return 'https://via.placeholder.com/400x300.png?text=No+Image';
        }

        if (strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }

        $uploadDriver = env('UPLOAD_DRIVER', 'local');

        if ($uploadDriver === 'cloudinary') {
            return 'https://via.placeholder.com/400x300.png?text=No+Image';
        }

        if (strpos($imageUrl, '/') === 0) {
            return url($imageUrl);
        }

        return url('/images/products/' . $imageUrl);
    }

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
            'imageUrl' => $this->getFullImageUrl($this->imageUrl),
            'cloudinary_public_id' => $this->cloudinary_public_id,
            'inventory' => new InventoryResource($this->whenLoaded('inventory')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
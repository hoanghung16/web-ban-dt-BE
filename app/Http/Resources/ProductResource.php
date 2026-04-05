<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Get full image URL - only return Cloudinary URLs or placeholder
     * Local URLs are not persisted on Render (ephemeral filesystem)
     * Seeded products will show placeholder image
     */
    private function getFullImageUrl($imageUrl)
    {
        // If empty or null - return placeholder
        if (empty($imageUrl)) {
            return 'https://via.placeholder.com/400x300.png?text=No+Image';
        }
        
        // Already a full URL (Cloudinary or external) - return as-is
        if (strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }
        
        // Local relative URL (/images/products/...) 
        // These don't exist on Render (ephemeral filesystem)
        // Return placeholder image
        if (strpos($imageUrl, '/') === 0) {
            return 'https://via.placeholder.com/400x300.png?text=No+Image';
        }
        
        return 'https://via.placeholder.com/400x300.png?text=No+Image';
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
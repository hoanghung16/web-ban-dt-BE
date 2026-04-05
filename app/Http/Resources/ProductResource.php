<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Get full image URL - handle both local and cloud URLs
     */
    private function getFullImageUrl($imageUrl)
    {
        // If URL is empty or null
        if (empty($imageUrl)) {
            return $this->getPlaceholderImage();
        }
        
        // Already a full URL (starts with http)
        if (strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }
        
        // Local URL (starts with /)
        if (strpos($imageUrl, '/') === 0) {
            // On production (Render), local URLs won't work - return placeholder
            if (env('APP_ENV') === 'production') {
                return $this->getPlaceholderImage();
            }
            // Local dev - return full URL
            return url($imageUrl);
        }
        
        return $this->getPlaceholderImage();
    }
    
    /**
     * Get placeholder image for missing images
     */
    private function getPlaceholderImage()
    {
        // Use Cloudinary placeholder (works everywhere)
        return 'https://res.cloudinary.com/demo/image/fetch/w_400,h_400,c_fill/https://via.placeholder.com/400x400?text=Product+Image';
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
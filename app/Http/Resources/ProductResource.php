<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Get full image URL - convert relative URLs to absolute
     * Handles both Cloudinary URLs and local storage URLs
     */
    private function getFullImageUrl($imageUrl, $request = null)
    {
        // If empty or null
        if (empty($imageUrl)) {
            return null;
        }
        
        // Already a full URL (Cloudinary or external)
        if (strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }
        
        // Local relative URL - convert to full using dynamic base URL
        if (strpos($imageUrl, '/') === 0) {
            // On Render, APP_URL might be localhost
            // Instead, use request's current scheme and host
            $baseUrl = $request ? $request->getSchemeAndHttpHost() : url('/');
            return $baseUrl . $imageUrl;
        }
        
        return null;
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
            'imageUrl' => $this->getFullImageUrl($this->imageUrl, $request),
            'cloudinary_public_id' => $this->cloudinary_public_id,
            'inventory' => new InventoryResource($this->whenLoaded('inventory')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
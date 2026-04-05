<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        // Lazy initialize - only create when needed
        $this->cloudinary = null;
    }

    /**
     * Get Cloudinary instance (lazy initialized)
     */
    private function getCloudinary()
    {
        if (is_null($this->cloudinary)) {
            $this->cloudinary = new Cloudinary(
                "cloudinary://" . 
                env('CLOUDINARY_API_KEY') . ":" . 
                env('CLOUDINARY_API_SECRET') . "@" . 
                env('CLOUDINARY_CLOUD_NAME')
            );
        }
        return $this->cloudinary;
    }

    /**
     * Upload file to Cloudinary
     * 
     * @param $file - File object from request
     * @param string $folder - Folder in Cloudinary
     * @return array - URL and public_id
     */
    public function uploadFile($file, $folder = 'products')
    {
        try {
            $response = $this->getCloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => $folder,
                    'resource_type' => 'auto',
                ]
            );

            return [
                'url' => $response['secure_url'],
                'public_id' => $response['public_id'],
                'success' => true
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete file from Cloudinary
     * 
     * @param string $public_id
     * @return bool
     */
    public function deleteFile($public_id)
    {
        try {
            $this->getCloudinary()->uploadApi()->destroy($public_id);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get optimized image URL
     * 
     * @param string $public_id
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getOptimizedUrl($public_id, $width = null, $height = null)
    {
        try {
            $url = $this->getCloudinary()->image($public_id)->toUrl();
            
            // Add query parameters for resizing
            if ($width && $height) {
                $url = $url . "?w={$width}&h={$height}&c=fill";
            } elseif ($width) {
                $url = $url . "?w={$width}";
            } elseif ($height) {
                $url = $url . "?h={$height}";
            }
            
            return $url;
        } catch (\Exception $e) {
            return '';
        }
    }
}

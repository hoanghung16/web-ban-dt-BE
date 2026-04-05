<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminSyncController extends Controller
{
    /**
     * Trigger product images sync to Cloudinary
     * POST /api/admin/sync-images
     * 
     * Only accessible to admins
     */
    public function syncProductImages(Request $request)
    {
        try {
            // Run sync command
            $exitCode = Artisan::call('products:sync-images-cloudinary');
            
            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product images synced to Cloudinary successfully',
                    'exit_code' => $exitCode
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sync completed with errors',
                    'exit_code' => $exitCode
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

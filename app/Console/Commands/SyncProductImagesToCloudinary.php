<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Str;

class SyncProductImagesToCloudinary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Use --dry-run to preview without updating DB.
     */
    protected $signature = 'products:sync-images-cloudinary {--dry-run}';

    /**
     * The console command description.
     */
    protected $description = 'Upload local product images to Cloudinary and update imageUrl in DB';

    public function handle(CloudinaryService $cloudinaryService)
    {
        $dryRun = (bool) $this->option('dry-run');
        $updated = 0;
        $skipped = 0;
        $failed = 0;

        $products = Product::all();

        foreach ($products as $product) {
            $imageUrl = $product->imageUrl;

            if (empty($imageUrl)) {
                $skipped++;
                continue;
            }

            if (Str::startsWith($imageUrl, 'http')) {
                $skipped++;
                continue;
            }

            $relativePath = $imageUrl;
            if (!Str::startsWith($relativePath, '/')) {
                $relativePath = '/images/products/' . $relativePath;
            }

            $fullPath = public_path(ltrim($relativePath, '/'));

            if (!file_exists($fullPath)) {
                $this->warn("Missing file for product #{$product->id}: {$fullPath}");
                $failed++;
                continue;
            }

            if ($dryRun) {
                $this->line("[Dry run] Would upload: {$fullPath}");
                $updated++;
                continue;
            }

            $result = $cloudinaryService->uploadFile(new File($fullPath), 'products');

            if (!$result['success']) {
                $this->error("Upload failed for product #{$product->id}: {$result['error']}");
                $failed++;
                continue;
            }

            $product->imageUrl = $result['url'];
            $product->cloudinary_public_id = $result['public_id'];
            $product->save();

            $updated++;
        }

        $this->info("Done. Updated: {$updated}, Skipped: {$skipped}, Failed: {$failed}");

        return Command::SUCCESS;
    }
}

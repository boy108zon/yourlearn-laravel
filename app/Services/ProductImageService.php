<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageService
{
    
    public function storeAndResizeImage(UploadedFile $file, string $folder, string $disk = null ,array $additionalParams = []): string
    {
        
        $disk = $disk ?? config('filesystems.default');
        $fileName = $this->generateFileName($file,$additionalParams);
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->resize(800, 800);
        $this->storeImage($image, $folder, $fileName, $disk);
        return "{$folder}/{$fileName}";
    }

    private function generateFileName(UploadedFile $file, array $additionalParams): string
    {
        $baseName = time();
        $baseName = $additionalParams['product_name'].'_'.$additionalParams['sku'] . '-' . $baseName;
        $baseName = preg_replace('/[^a-z0-9]+/i', '-', ucwords(strtolower($baseName)));
        $extension = $file->getClientOriginalExtension();
        return "{$baseName}.{$extension}";
    }
    
    private function storeImage($image, string $folder, string $fileName, string $disk): void
    {
        if ($disk === 'local' || $disk === 'public') {
            $image->save(storage_path("app/public/{$folder}/{$fileName}"));
        } else {
            Storage::disk($disk)->put("{$folder}/{$fileName}", (string) $image->encode());
        }
    }

    public function getImageUrl(string $path, string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');

        if ($disk === 'local' || $disk === 'public') {
            return $this->getLocalOrPublicUrl($path, $disk);
        }

        if ($disk === 's3') {
            return $this->getS3Url($path);
        }

        if ($disk === 'bitbucket') {
            return $this->getBitbucketUrl($path);
        }

        return asset('images/default-product-image.jpg');
    }

    private function getLocalOrPublicUrl(string $path, string $disk): string
    {
        if ($disk === 'public' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        if ($disk === 'local' && Storage::disk('local')->exists($path)) {
            return asset('storage/' . $path);
        }
        return asset('images/default-product-image.jpg');
    }

    private function getS3Url(string $path): string
    {
        if (Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->url($path);
        }

        return asset('images/default-product-image.jpg');
    }

    private function getBitbucketUrl(string $path): string
    {
        return 'https://bitbucket.org/your-repo-name/images/' . $path;
    }

    public function deleteImage(string $path, string $disk = null): void
    {
        $disk = $disk ?? config('filesystems.default');
        switch ($disk) {
            case 'local':
            case 'public':
                
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
                break;

            case 's3':
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
                break;

            case 'bitbucket':
                $this->deleteFromBitbucket($path);
                break;

            default:
                throw new \Exception("Unsupported disk type: {$disk}");
        }
    }

    private function deleteFromBitbucket(string $path): void
    {
        $bitbucketApiUrl = "https://api.bitbucket.org/2.0/repositories/your-repo-name/images/{$path}";
        
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->request('DELETE', $bitbucketApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer your-oauth-token'
                ]
            ]);
            
            if ($response->getStatusCode() !== 204) {
                throw new \Exception("Failed to delete from Bitbucket.");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error deleting file from Bitbucket: " . $e->getMessage());
        }
    }
}

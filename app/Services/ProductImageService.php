<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Facades\Image;

class ProductImageService
{
    
    public function SaveImage(UploadedFile $file, string $folder, array $additionalParams = []): string
    {
        
        $disk = config('filesystems.default');
        $fileName = $this->generateFileName($file,$additionalParams);
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $this->storeImage($image, $folder, $fileName, $disk);
        return "{$folder}/{$fileName}";
    }

    public function storeAndResizeImage(UploadedFile $file, string $folder, array $additionalParams = []): array
    {
        
        $disk =  config('filesystems.default');
        $fileName = $this->generateFileName($file,$additionalParams);
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
       
        /*$image->resize(1200, 1200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });*/

        $this->storeImage($image, $folder, $fileName, $disk);
        $imagePath = "{$folder}/{$fileName}";

        $thumbnailName = 'thumb_' . $fileName;
        $thumbnail = $manager->read($file->getPathname());
        $thumbnail->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    
        $thumbnailPath = "{$folder}/thumbnails/{$thumbnailName}";
        $this->storeImage($thumbnail, $folder . '/thumbnails', $thumbnailName, $disk);
        

        return [
            'image_url' => $imagePath,
            'thumbnail_url' => $thumbnailPath
        ];
    }

    public function storeAndGenerateThumbnail(UploadedFile $file, string $folder, array $additionalParams = []): string
    {
       
        $disk = config('filesystems.default');
        $fileName = $this->generateFileName($file, $additionalParams);
        
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
       
        $thumbnailFileName = 'thumb_' . $fileName;
        $thumbnail = $image->resize(200, 200);
        $this->storeImage($thumbnail, $folder, $thumbnailFileName, $disk);
        return "{$folder}/{$fileName}";
    }
    
    private function generateFileName(UploadedFile $file, array $additionalParams): string
    {

        $ImageRandomTime = time();
        $originalName = $file->getClientOriginalName();
        $fileNameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        $fileNameToLower=ucwords(strtolower($fileNameWithoutExtension));
        
        $fileName=$fileNameToLower.'_'.$additionalParams['product_name'].'_'.$additionalParams['sku'] . '-' . $ImageRandomTime;
        $baseName=preg_replace('/[^a-z0-9]+/i','_',$fileName);

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

    public function getImageUrl(?string $path): string
    {
       
        $disk = config('filesystems.default');
       
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

    public function deleteImage(string $path): void
    {
        $disk = config('filesystems.default');
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

    public function getImageSize(?string $path): int
    {
       
        $disk = config('filesystems.default');
       
        if ($disk === 'local' || $disk === 'public') {
            return $this->getLocalOrPublicFileSize($path);
        }

        if ($disk === 's3') {
            return $this->getS3FileSize($path);
        }

        if ($disk === 'bitbucket') {
            return $this->getBitbucketFileSize($path);
        }

        return 0;
    }
    
    protected function getLocalOrPublicFileSize(string $path): int
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->size($path);
        } else {
            return 0;
        }
    }

    protected function getS3FileSize(string $path): int
    {
        // For S3, you'd use the AWS SDK or the Laravel S3 storage methods to get the file size
        // Example using Laravel's Storage facade
        $file = Storage::disk('s3')->get($path);
        return strlen($file);
    }

    protected function getBitbucketFileSize(string $path): int
    {
        // Implement how you retrieve file size from Bitbucket
        // Bitbucket may not allow direct file size retrieval, so adjust accordingly
        return 0;
    }
}

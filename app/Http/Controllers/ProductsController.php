<?php
namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductsActionService;
use App\Services\ProductImageService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class ProductsController extends Controller
{
   
    protected $ProductsActionService;
    protected $ProductImageService;

    public function __construct(ProductsActionService $categoryActionService, ProductImageService $ProductImageService)
    {
        $this->middleware('auth');
        $this->ProductsActionService = $categoryActionService;
        $this->ProductImageService = $ProductImageService;
    }

    public function index(ProductsDataTable $dataTable)
    {
        $user = auth()->user();
        $actions = $this->ProductsActionService->getActions($user);
        return $dataTable->render('products.index', compact('actions'));
    }

    public function create()
    {
        $categories = Category::all(); 
        return view('products.create', compact('categories'));
    }

    public function store(CreateProductRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (empty($validated['sku'])) {
            $validated['sku'] = $this->generateSKU($validated['name'], $request->category_id);
        }

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $additionalParams = [
                    'sku' => $request->sku,
                    'product_name' => $request->name,
                ];
                $imagePaths = $this->ProductImageService->storeAndGenerateThumbnail($image, 'products/images', 'public', $additionalParams);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imagePaths['image'], // Full image
                    'thumbnail_url' => $imagePaths['thumbnail'], // Thumbnail image
                    'is_active' => 1,
                ]);
            }
        }

        if ($request->has('category_id')) {
            $product->categories()->sync($request->category_id);
        }

        return redirect()->route('products.index')->with('swal', [
            'message' => 'Product created successfully!',
            'type' => 'success',
        ]);
    }

    public function store27(CreateProductRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (empty($validated['sku'])) {
            $validated['sku'] = $this->generateSKU($validated['name'], $request->category_id);
        }
       
        $product = Product::create($validated);

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {
                $additionalParams = [
                    'sku' => $request->sku,
                    'product_name' => $request->name,
                ];
                $imagePath = $this->ProductImageService->storeAndResizeImage($image, 'products/images','public', $additionalParams);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imagePath,
                    'is_active' => 1,  
                ]);
            }
        }
       

        if ($request->has('category_id')) {
            $product->categories()->sync($request->category_id); 
        }

        return redirect()->route('products.index')->with('swal', [
            'message' => 'Product created successfully!',
            'type' => 'success',
        ]);
    }
    
    public function edit(Product $product)
    {
        $categories = Category::all(); 
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
       
        $product->update($validated);

        if ($request->has('category_id')) {
            $product->categories()->sync($request->category_id);
        }

        return redirect()->route('products.index')->with('swal', [
            'message' => 'Product updated successfully!',
            'type' => 'success',
        ]);
    }

    public function editproductImages(Product $product, ProductImageService $productImageService)
    {
        
        $images = $product->images()
        ->select('id', 'image_url', 'is_primary')
        ->whereNotNull('image_url') 
        ->get();
        
        if ($images->isEmpty()) {
            $images = [];
        }else{
            $images = $images->map(function ($image) use ($product,$productImageService) {
                return [
                    'id' => $image->id,
                    'image_url' =>$productImageService->getImageUrl($image->image_url), 
                    'name' => $product->name,
                    'size'=> $productImageService->getImageSize($image->image_url),
                    'is_primary' => $image->is_primary,
                ];
            });
        }
       
        return view('products.edit-product-images', compact('product','images'));
    }
    
    public function primaryImageStatus(Request $request,Product $product)
    {
        
        $imageId = $request->input('image_id');
        $isPrimary = $request->input('is_primary');

        if (!$product) {
            return response()->json(['status'=>1,'msg' => 'Product not found.'], 404);
        }

        $ProductImage = ProductImage::find($imageId);

        if (!$ProductImage) {
            return response()->json(['status'=>1,'msg' => 'Image not found.'], 404);
        }

        if ($isPrimary) {
            $primaryImageCount = ProductImage::where('product_id', $product->id)->where('is_primary', 1)->count();
            if ($primaryImageCount >= 2) {
                return response()->json([
                    'msg' => 'You can only have a maximum of two primary images.',
                    'status'=>1
                ], 200);
            }
        }

        $ProductImage->update(['is_primary' => $isPrimary]);
        return response()->json(['msg' => 'Saved successfully.','status'=> 0],200);
    }

    public function storeProductImages(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'images.*' => 'file|mimes:jpeg,png,webp,jpg|max:5240|dimensions:max_width=5000,max_height=8000',
            ], [
                'images.*.file' => 'Each image must be a valid file.',
                'images.*.mimes' => 'Only JPEG, PNG, and JPG files are allowed.',
                'images.*.max' => 'Each image must not exceed 5 MB.',
                'images.*.dimensions' => 'Each image must be 100x100 pixels or smaller.',
            ]);

            $uploadedImages = [];

            if ($request->hasFile('images')) {
                $additionalParams = [
                    'sku' => $product->sku,
                    'product_name' => $request->name,
                ];

                foreach ($request->file('images') as $image) {
                    $imagePath = $this->ProductImageService->storeAndResizeImage($image, 'products/images', $additionalParams);

                    $uploadedImage = $product->images()->create([
                        'image_url' => $imagePath['image_url'],
                        'thumbnail_url' => $imagePath['thumbnail_url'],
                    ]);

                    $uploadedImages[] = $uploadedImage;
                }
            }

            if (empty($uploadedImages)) {
                return response()->json(['status'=> 1,'message' => 'No images were uploaded.'], 400);
            }

            return response()->json([
                'status'=> 0,
                'message'=>'File uploaded & saved successfully!',
                'image_id' => $uploadedImages[0]->id,
                'product_id' => $uploadedImages[0]->product_id,
                'image_url' => $this->ProductImageService->getImageUrl($uploadedImages[0]->image_url),
            ], 200);
        } catch (ValidationException $e) {
            $message = $e->errors();
            $firstError = reset($message);
            return response()->json([
                'status'=>1,
                'message' => $firstError[0] ?? 'Validation failed.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> 1,
                'message' => 'Something went wrong while uploading images.',
                'details' => $e->getMessage(),
            ], 200);
        }
    }

    public function removeProductImage(Request $request,Product $product)
    {
    
        $request->validate([
            'image_id' => 'required|exists:product_images,id',
        ]);

        if (!empty($product)) {
        
            $image = ProductImage::findOrFail($request->image_id);
            if ($image->image_url) {
                $this->ProductImageService->deleteImage($image->image_url);
            }

            $image->delete();
            return response()->json(['success' => true,'message' => 'Removed successfully.'],200);
        }

        return response()->json(['success' => false], 404);  
    }

    public function editproductThumnails(Product $product, ProductImageService $productImageService)
    {
        $productThumbnails = $product->images()
        ->select('id', 'thumbnail_url', 'is_primary')
        ->whereNotNull('thumbnail_url') 
        ->get();

        $productImages=$product->images()->get();
        
        if ($productThumbnails->isEmpty()) {
            $productThumbnails = [];
        }else{
            $productThumbnails = $productThumbnails->map(function ($image) use ($product,$productImageService) {
                return [
                    'id' => $image->id,
                    'image_url' =>$productImageService->getImageUrl($image->thumbnail_url), 
                    'name' => $product->name,
                    'size'=> $productImageService->getImageSize($image->thumbnail_url),
                    'is_primary' => $image->is_primary,
                ];
            });
        }
      
        return view('products.edit-thumbnails', compact('product','productThumbnails','productImages','productImageService'));
    }
    
    public function primaryImageThumnails(Request $request,Product $product)
    {
        
        $imageId = $request->input('image_id');
        $isPrimary = $request->input('is_primary');

        if (!$product) {
            return response()->json(['status'=>1,'msg' => 'Product not found.'], 404);
        }

        $ProductImage = ProductImage::find($imageId);

        if (!$ProductImage) {
            return response()->json(['status'=>1,'msg' => 'Image not found.'], 404);
        }

        if ($isPrimary) {
            $primaryImageCount = ProductImage::where('product_id', $product->id)->where('is_primary', 1)->count();
            if ($primaryImageCount >= 2) {
                return response()->json([
                    'msg' => 'You can only have a maximum of two primary images.',
                    'status'=>1
                ], 200);
            }
        }

        $ProductImage->update(['is_primary' => $isPrimary]);
        return response()->json(['msg' => 'Saved successfully.','status'=> 0],200);
    }

    public function storeProductThumnails(Request $request, Product $product)
    {
       
        $validated = $request->validate([
            'images.*' => 'file|mimes:jpeg,png,jpg|max:10240|dimensions:max_width=5000,max_height=5000', // 10 MB limit
        ]);
        
        $uploadedImages = [];

        if ($request->hasFile('images')) {
            $additionalParams = [
                'sku' => $product->sku,
                'product_name' => $request->name,
            ];

            foreach ($validated['images'] as $image) {
                $imagePath = $this->ProductImageService->storeAndGenerateThumbnail($image, 'products/images/thumbnails', $additionalParams);

                $uploadedImage = $product->images()->create([
                    'thumbnail_url' => $imagePath,
                ]);

                $uploadedImages[] = $uploadedImage;
            }
        }

        return response()->json([
            'image_id' => $uploadedImage->id,
            'product_id'=>$uploadedImage->product_id,
            'image_url' => $this->ProductImageService->getImageUrl($uploadedImages[0]->thumbnail_url), 
        ],200);
    }

    public function removeProductThumnails(Request $request,Product $product)
    {
    
        $request->validate([
            'image_id' => 'required|exists:product_images,id',
        ]);

        if (!empty($product)) {
        
            $image = ProductImage::findOrFail($request->image_id);
            if ($image->thumbnail_url) {
                $this->ProductImageService->deleteImage($image->thumbnail_url);
            }

            $image->delete();
            return response()->json(['success' => true,'message' => 'Removed successfully.'],200);
        }

        return response()->json(['success' => false], 404);  
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('swal', [
            'message' => 'Product deleted permanently!',
            'type' => 'success'
        ]);
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
   
    private function generateSKU($productName, $categoryId = null)
    {
        $productPrefix = strtoupper(substr($productName, 0, 3));
        $category = Category::find($categoryId);
        $categoryAbbreviation = strtoupper(substr($category[0]->name, 0, 3)); 
        $randomNumber = rand(100, 999).$category[0]->id;
        $sku = $productPrefix . '-' . $categoryAbbreviation . '-' . $randomNumber;
        return substr($sku, 0, 12);
    }
}

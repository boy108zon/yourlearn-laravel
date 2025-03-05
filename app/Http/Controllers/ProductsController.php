<?php
namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductsActionService;
use App\Services\ProductImageService;
use Illuminate\Support\Str;

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
        
        if ($request->hasFile('image_url')) {
            $additionalParams = [
                'sku' => $request->sku,
                'product_name' => $request->name,
            ];
            $validated['image_url'] = $this->ProductImageService->storeAndResizeImage($request->file('image_url'), 'products/images','public', $additionalParams);
        }

        $product = Product::create($validated);

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

        if ($request->hasFile('image_url')) {

            $additionalParams = [
                'sku' => $request->sku,
                'product_name' => $request->name,
            ];

            if ($product->image_url) {
                $this->ProductImageService->deleteImage($product->image_url,'public');
            }
            
            $validated['image_url'] = $this->ProductImageService->storeAndResizeImage($request->file('image_url'), 'products/images','public', $additionalParams);
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

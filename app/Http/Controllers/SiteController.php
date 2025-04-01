<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ProductImageService;
class SiteController extends Controller
{
    protected $ProductImageService;

    public function __construct(ProductImageService $ProductImageService)
    {
        $this->ProductImageService = $ProductImageService;
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::query();

        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->has('price_range')) {
            $range = explode('-', $request->price_range);
            $query->whereBetween('price', [$range[0], $range[1]]);
        }

        $products = $query->with('images')->paginate(8);

        if ($request->ajax()) {
            $hasMorePages = $products->hasMorePages();
            return response()->json([
                'productList' => view('public-products.product-list', compact('products'))->render(),
                'hasMorePages' => $hasMorePages,
            ]);
        }

        return view('public-products.index', compact('products', 'categories'));
    }

    public function getProducts(Request $request)
    {
        
        $categoryId = $request->input('category_id');
        $priceRange = $request->input('price_range');
        $searchQuery = $request->input('search');
        $page = $request->input('page', 1);

        $query = Product::query();

        if ($request->has('category_id') && $request->category_id !== null) {
            $categoryId = $request->category_id;
            $query->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }

        if ($request->has('price_range') && $priceRange !== null) {
            if (isset($priceRange['min']) && isset($priceRange['max'])) {
                $minPrice = $priceRange['min'];
                $maxPrice = $priceRange['max'];

                if ($minPrice !== null && $maxPrice !== null) {
                    $query->whereBetween('price', [$minPrice, $maxPrice]);
                } elseif ($minPrice !== null) {
                    $query->where('price', '>=', $minPrice);
                } elseif ($maxPrice !== null) {
                    $query->where('price', '<=', $maxPrice);
                }
            }
        }

        if ($request->has('search') && $searchQuery !== '') {
            $query->where('name', 'LIKE', '%' . $searchQuery . '%');
        }

        $products = $query->with('categories', 'images')->paginate(8);
        return response()->json([
            'products' => $products
        ]);
    }

    public function show($slug, $id)
    {
        $productImageService=$this->ProductImageService;
        $showSidebar = false;
        $product = Product::with(['categories'])->where('slug', $slug)->where('id', $id)->first();
        
        if (!$product) {
            return redirect()->route('site')->with('swal', [
                'message' => 'Product not found.',
                'type' => 'info',
            ]);
        }

       
        $ratings = $product->ratings()->with('user')->latest()->take(5)->get();
        $ratingCounts = $ratings->groupBy('rating')->map(fn($group) => $group->count());
        $averageRating = $ratings->avg('rating') ?? 0;
        $ratingCount = $ratings->count();
        $ratingPercentages = $ratingCounts->mapWithKeys(fn($count, $star) => [$star => ($count / $ratingCount) * 100]);
        
        $HighestPromoCodeDiscount = $product->getHighestPromoCodeDiscount();
        //dd($HighestPromoCodeDiscount);
        return view('public-products.show', compact('product','showSidebar','HighestPromoCodeDiscount','productImageService','ratings','averageRating','ratingCounts','ratingPercentages','ratingCount'));
    }
}

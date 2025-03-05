<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SiteController extends Controller
{
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

        $products = $query->paginate(10);

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

        $products = $query->with('categories')->paginate(8);
        return response()->json([
            'products' => $products
        ]);
    }

}

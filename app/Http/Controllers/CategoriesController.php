<?php

namespace App\Http\Controllers;

use App\DataTables\CategoriesDataTable;
use App\DataTables\CategoryProductDataTable;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryActionService;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    protected $categoryActionService;

    public function __construct(CategoryActionService $categoryActionService)
    {
        $this->middleware('auth');
        $this->categoryActionService = $categoryActionService;
    }

    public function index(CategoriesDataTable $dataTable)
    {
        $user = auth()->user();
        $actions = $this->categoryActionService->getActions($user);
        return $dataTable->render('categories.index', compact('actions'));
    }

    public function create()
    {
        $categories = Category::all(); 
        return view('categories.create', compact('categories'));
    }

    public function store(CreateCategoryRequest $request)
    {
        $validated = $request->validated();
    
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
    
        Category::create($validated);
    
        return redirect()->route('categories.index')->with('swal', [
            'message' => 'Category created successfully!',
            'type' => 'success',
        ]);
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
       
        $validated = $request->validated();
        $category->update($validated);
       
        return redirect()->route('categories.index')->with('swal', [
            'message' => 'Category updated successfully!',
            'type' => 'success',
        ]);
    }

    public function destroy(Category $category)
    {
        $category->products()->detach();
        $category->delete();
        return redirect()->route('categories.index')->with('swal', [
            'message' => 'Category deleted successfully!',
            'type' => 'success'
        ]);
    }
    

    public function show(Category $category)
    {
        $products = $category->products;
        return view('categories.show', compact('category', 'products'));
    }

    public function productsIndexwww(Category $category)
    {
        $products = $category->products;
        return view('categories.products.index', compact('category', 'products'));
    }

    public function productsIndex(CategoryProductDataTable $dataTable, Category $category)
    {
        return $dataTable->with('category', $category)->render('categories.products.index', compact('category'));
    }
}

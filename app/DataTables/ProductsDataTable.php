<?php 

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use App\Services\ProductImageService;
use Carbon\Carbon;

class ProductsDataTable extends DataTable
{
    protected $userPermissions;
    protected $filesystemDisk;

    public function __construct()
    {
        $this->filesystemDisk = env('FILESYSTEM_DISK', 'public');
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }
    
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($product) {
                $userPermissions = $this->userPermissions;
                return view('products.action', compact('product', 'userPermissions'));
            })
            ->setRowId('id')
            ->editColumn('name', function ($product) {
                return '' . ucfirst($product->name) . '';
            })
            ->editColumn('name', function ($product) {
                $firstImage = $product->images()->where(['is_active' => 1])->first();
            
                if (!$firstImage) {
                    return ucfirst($product->name);
                }
            
                $productId = $product->id;
                $averageRating = $product->average_rating ?? 0; // Correct variable name
                $ratingCount = $product->rating_count ?? 0;
            
                $productReviews = view('products.product-reviews', [
                    'productId' => $productId,
                    'averageRating' => $averageRating, // Pass the correct variable
                    'ratingCount' => $ratingCount
                ])->render();
            
                $imageUrl = app(ProductImageService::class)->getImageUrl($firstImage->image_url);
            
                $popoverContent = '
                    <div class="d-flex flex-column align-items-center justify-content-center w-100" style="max-height: 90vh; overflow: auto; background-color: #fff; padding: 10px;">
                        <img src="' . e($imageUrl) . '" class="img-fluid w-100" alt="' . e($product->name) . '" style="object-fit: cover; height: auto;">
                        <div class="mt-2">' . $productReviews . '</div>
                    </div>';
            
                return '<a href="javascript:void(0);" 
                    class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover position-relative"
                    data-bs-toggle="popover" 
                    data-bs-trigger="focus" 
                    data-bs-title="' . e($product->name) . '" 
                    data-bs-content="' . htmlspecialchars($popoverContent, ENT_QUOTES, 'UTF-8') . '"
                    data-bs-html="true" 
                    data-bs-placement="bottom">
                    ' . ucfirst(e($product->name)) . '
                </a>';
            })
            ->editColumn('is_active', function ($product) {
                $badgeClass = $product->is_active == 1 ? 'badge text-bg-primary rounded-pill' : 'badge text-bg-danger rounded-pill';
                $statusText = $product->is_active == 1 ? 'Active' : 'Inactive';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($statusText) . '</span>';
            })
            ->rawColumns(['action', 'name', 'is_active']);
    }

    public function query(Product $model): QueryBuilder
    {
        $query = $model->newQuery();
        
        // Filter by date if provided
        if (request()->filled('start_date') && request()->filled('end_date')) {
            $startDate = request()->input('start_date');
            $endDate = request()->input('end_date');

            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        if (request()->filled('filterPrice')) {
            $priceFilter = request()->input('filterPrice');
    
            if ($priceFilter === 'low') {
                $query->orderBy('price', 'asc'); 
            } elseif ($priceFilter === 'high') {
                $query->orderBy('price', 'desc'); 
            }
        }

        $query->with('images');

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ])
            ->addTableClass('table py-2 table-bordered table-sm table-striped table-responsive w-100')
            ->parameters([
                'scrollX' => false,
                'lengthMenu' => [
                    [10, 25, 50, 100, -1],
                    ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
                ],
                'language' => [
                    'className' => 'form-control form-control-solid w-250px ps-14',
                    'searchPlaceholder' => 'Search Categories',
                    'zeroRecords' => 'No data available in this table. Please apply filters to get results.',
                    'emptyTable' => 'No matching records found',
                ],
                'columnDefs' => [
                    [
                        'targets' => 0,  
                        'visible' => false,
                    ],
                ],
            ])->postAjax(route('products.index'));
    }

    
    public function getColumns(): array
    {
        $canEdit = $this->userPermissions->contains('edit-product');
        $canDelete = $this->userPermissions->contains('remove-product');
        $canCreate = $this->userPermissions->contains('create-product');

        $columns = [
            Column::make('id')->width('10%'),
            Column::make('name')->width('25%'),
            Column::make('price')->width('10%'),
            Column::make('stock_quantity')->title('Qty')->width('10%'),
            Column::make('weight')->width('10%'),
            Column::make('sku')->width('10%'),
            Column::make('is_active')->title('status')->width('10%'),
        ];

        if ($canEdit || $canDelete || $canCreate) {
            $columns[] = Column::computed('action')
                ->exportable(true)
                ->printable(true)
                ->width('15%')
                ->addClass('text-center');
        }

        return $columns;
    }

    public function show(Category $category)
    {
        return view('products.show', compact('category'));
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'products_' . date('YmdHis');
    }
}

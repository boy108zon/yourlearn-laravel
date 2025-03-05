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

class CategoryProductDataTable extends DataTable
{
    protected $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }
    
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($product) {
                $userPermissions = $this->userPermissions;
                return view('categories.products.action', compact('product', 'userPermissions'));
            })
            ->setRowId('id')
            ->editColumn('name', function ($product) {
                $imageUrl = app(ProductImageService::class)->getImageUrl($product->image_url, 'public'); 
                return '<a href="javascript:void(0);"  class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover position-relative"
                            data-bs-toggle="popover" 
                            data-bs-trigger="focus" 
                            data-bs-title="' . e($product->name) . '" 
                            data-bs-content="<img src=\'' . $imageUrl . '\' class=\'img-fluid\' alt=\'' . e($product->name) . '\'>">
                            '.ucfirst($product->name).'
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
        $categoryId = request()->query('category');
        $query = $model->newQuery();

        if ($categoryId) {
            $query->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }

        if (request()->has('extra_column_value')) {
            $query->whereHas('categories', function ($query) {
                $query->wherePivot('extra_column', request()->query('extra_column_value'));
            });
        }

        if (request()->filled('start_date') && request()->filled('end_date')) {
            $startDate = request()->input('start_date');
            $endDate = request()->input('end_date');
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

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
                    'searchPlaceholder' => 'Search Products',
                    'zeroRecords' => 'No data available in this table.',
                    'emptyTable' => 'No matching records found',
                ],
                'columnDefs' => [
                    ['targets' => 0, 'visible' => false], 
                ],
            ])->postAjax(route('categories.productsIndex', ['category' => request()->route('category')->id]));
    }

    
    public function getColumns(): array
    {
        $canEdit = $this->userPermissions->contains('edit-product');
        $canDelete = $this->userPermissions->contains('remove-product');
        $canCreate = $this->userPermissions->contains('create-product');

        $columns = [
            Column::make('id')->width('10%'),
            Column::make('name')->title('Product Name')->width('25%'),
            Column::make('price')->width('10%'),
            Column::make('stock_quantity')->title('Qty')->width('5%'),
            Column::make('weight')->width('5%'),
            Column::make('sku')->width('10%'),
            Column::make('is_active')->title('status')->width('5%'),
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
        return 'categoryproducts_' . date('YmdHis');
    }
}

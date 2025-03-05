<?php 

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class CategoriesDataTable extends DataTable
{
    protected $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }
    
    /**
     * Build the DataTable class.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Results from query() method.
     */
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($category) {
                $userPermissions = $this->userPermissions;
                return view('categories.action', compact('category', 'userPermissions'));
            })
            ->setRowId('id')
           
            ->editColumn('name', function ($category) {
                $productCount = $category->products()->count();  
                $button = '
                    <a href="' . route('categories.products.productsIndex', $category->id) . '" title="See associated products" class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover position-relative">
                        ' . ucfirst($category->name) . '
                        <span class="badge rounded-pill btn-bd-primary ">
                            ' . $productCount . '
                        </span>
                    </a>';
                return $button;
            })
            
            ->editColumn('is_active', function ($category) {
                $badgeClass = $category->is_active == 1 ? 'badge text-bg-primary rounded-pill' : 'badge text-bg-danger rounded-pill';
                $statusText = $category->is_active == 1 ? 'Active' : 'Inactive';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($statusText) . '</span>';
            })
            ->rawColumns(['action', 'name', 'is_active']);
    }

    public function query(Category $model): QueryBuilder
    {
        $query = $model->newQuery();
        $query->withCount('products');

        // Filter by date if provided
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
            ->setTableId('categories-table')
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
            ])->postAjax(route('categories.index'));
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $canEdit = $this->userPermissions->contains('edit-category');
        $canDelete = $this->userPermissions->contains('remove-category');
        $canAssign = $this->userPermissions->contains('assign-category');

        $columns = [
            Column::make('id')->width('10%'),
            Column::make('name')->width('15%'),
            Column::make('description')->width('25%'),
            //Column::make('image_url')->width('25%'),
            Column::make('is_active')->width('5%'),
        ];

        if ($canEdit || $canDelete || $canAssign) {
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
        return view('categories.show', compact('category'));
    }

    protected function filename(): string
    {
        return 'Categories_' . date('YmdHis');
    }
}

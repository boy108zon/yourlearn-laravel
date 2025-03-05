<?php
namespace App\DataTables;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class MenusDataTable extends DataTable
{
    protected $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }
    
    public function dataTable($query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->addColumn('action', function ($menu) {
            $userPermissions = $this->userPermissions;
            return view('menus.action', compact('menu', 'userPermissions'));
        })
        ->setRowId('id')
        ->addColumn('children', function ($menu) {
            return view('menus.child_menus', ['userPermissions'=>$this->userPermissions,'parentMenu' => $menu, 'menus' => $menu->children])->render();
        })
        ->rawColumns(['action', 'children']);
}


    public function query(Menu $model): QueryBuilder
    {
        $query = $model->newQuery();
       if (request()->filled('start_date') && request()->filled('end_date')) {
            $startDate = request()->input('start_date');
            $endDate = request()->input('end_date');
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $query->with('children')->withTrashed()->where('parent_id', 0)->orderBy('sequence');
        return $query->orderBy('name');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('menus-table')
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
                    'searchPlaceholder' => 'Search Report',
                    'zeroRecords' => 'No data available in this table. Please apply filters to get results.',
                    'emptyTable' => 'No matching records found',
                ],
                'columnDefs' => [
                    [
                        'targets' => 0,  
                        'visible' => false,
                    ],
                    
                   
                ],
            ])->postAjax(route('menus.index'));
    }

   
    public function getColumns(): array
    {
        $columns = [
            Column::make('id')->width('10%'),
            Column::make('children')->title('')->width('20%'),
        ];
  
        return $columns;
    }

    public function show(Menu $menu)
    {
        return view('menus.show', compact('menu'));
    }

   
    protected function filename(): string
    {
        return 'Menus_' . date('YmdHis');
    }
}

<?php

namespace App\DataTables;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class PermissionsDataTable extends DataTable
{
    protected $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }
    
  
    public function dataTable(): EloquentDataTable
    {
        $permissions = $this->querySimple(new Permission());

        return (new EloquentDataTable($permissions))
            ->addColumn('action', function ($permission) {
                $userPermissions = $this->userPermissions;
                return view('permissions.action', compact('permission', 'userPermissions'));
            })
            ->setRowId('id')
            ->editColumn('created_at', function ($permission) {
                return $permission->created_at->format('d-m-Y H:i:s');
            })
            ->editColumn('name', function ($permission) {
                return ucfirst($permission->name);
            })
            ->editColumn('badge_color', function ($permission) {
                return '<span class="badge ' . $permission->badge_color . '">' . ucfirst($permission->name) . '</span>';
            })
            ->addColumn('module_name', function ($permission) {
                return $permission->module ? ucfirst($permission->module->name) : 'N/A';
            })
            ->rawColumns(['action', 'name', 'badge_color']);
    }


    public function querySimple(Permission $model): QueryBuilder
    {
        //return $model->newQuery();
        return $model->newQuery()->with('menus');
    }

    public function query(Permission $model): QueryBuilder
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

        return $query;
    }

    public function html(): HtmlBuilder{

        return $this->builder()
            ->setTableId('permission-table')
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
            ])->postAjax(route('permissions.index'));
    }
   
    /**
     * Get the dataTable columns definition.
     */
   
    protected function getColumns(): array
    {
        
        $canEdit = $this->userPermissions->contains('edit-permission');
        $canDelete = $this->userPermissions->contains('remove-role');
        
        $columns = [
            Column::make('id')->width('10%'),            
            Column::make('name')->width('50%'),
        ];
    
        if ($canEdit || $canDelete) {
            $columns[] = Column::computed('action')
            ->exportable(true)
            ->printable(true)
            ->width('30%')  
            ->addClass('text-center');
        }
    
        return $columns;
    }

    public function show(Permission $permission)
    {
        return view('roles.show', compact('permission'));
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Permissions_' . date('YmdHis');
    }
}

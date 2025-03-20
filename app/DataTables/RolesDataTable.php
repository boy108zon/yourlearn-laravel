<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class RolesDataTable extends DataTable
{
    protected $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }
    
   
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($role) {
                $userPermissions=$this->userPermissions;
                return view('roles.action', compact('role','userPermissions'));
            })
            ->setRowId('id')
            ->editColumn('created_at', function ($role) {
                return $role->created_at->format('d-m-Y H:i:s');
            })
            ->editColumn('name', function ($role) {
                return '' . ucfirst($role->name) . '';
            })->editColumn('badge_color', function ($role) {
                return '<span class="badge '.$role->badge_color.'">' . ucfirst($role->name) . '</span>';
            })
            
            ->rawColumns(['action', 'name','badge_color']);  
    }

    public function querySimple(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function query(Role $model): QueryBuilder
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
            ->setTableId('roles-table')
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
            ])->postAjax(route('roles.index'));
    }
   
    /**
     * Get the dataTable columns definition.
     */
   
    public function getColumns(): array
    {
        
        $canEdit = $this->userPermissions->contains('edit-role');
        $canDelete = $this->userPermissions->contains('remove-role');
        $canAssign = $this->userPermissions->contains('assign-permissions');

        $columns = [
            Column::make('id')->width('10%'),            
            Column::make('name')->width('40%'),
            Column::make('badge_color')->title('Looks As')->width('40%'),    
        ];
    
        if ($canEdit || $canDelete || $canAssign) {
            $columns[] = Column::computed('action')
            ->exportable(true)
            ->printable(true)
            ->width('30%')  
            ->addClass('text-center');
        }
    
        return $columns;
    }

    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Roles_' . date('YmdHis');
    }
}

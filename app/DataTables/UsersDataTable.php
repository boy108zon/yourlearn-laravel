<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;


class UsersDataTable extends DataTable
{
    public $start_date;
    public $end_date;

    protected $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($user) {
                $userPermissions = $this->userPermissions;
                return view('users.action', compact('user','userPermissions'));
            })
            ->setRowId('id')
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d-m-Y H:i:s');
            })
            ->editColumn('name', function ($user) {
                $roles = $user->roles;
                $roleBadges = '';
                foreach ($roles as $role) {
                    $badgeClass = $role->badge_color ?: 'bg-secondary';
                    $roleBadges .= '<span class="badge ' . $badgeClass . '">' . ucfirst($role->name) . '</span> ';
                }

                return $user->name . ' ' . $roleBadges;
            })
            ->rawColumns(['action', 'name']);
    }
     
    /**
     * Get the query source of dataTable.
     */
    public function querySimple(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function query(User $model): QueryBuilder
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
            ->setTableId('users-table')
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
            ])->postAjax(route('users.index'));;
    }
   
   
    public function getColumns(): array
    {
        
        $canEdit = $this->userPermissions->contains('edit-user');
        $canDelete = $this->userPermissions->contains('remove-user');
        $canRsetPassword = $this->userPermissions->contains('reset-password-for-users');

        $columns = [
            Column::make('id')->width('10%'),
            Column::make('name')->width('25%'),
            Column::make('email')->width('30%'),
            Column::make('created_at')->title('Date Created')->width('15%'),
        ];
    
        if ($canEdit || $canDelete || $canRsetPassword) {
            $columns[] = Column::computed('action')
                ->exportable(true)
                ->printable(true)
                ->width('20%')
                ->addClass('text-center');
        }
    
        return $columns;
    }
    

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}

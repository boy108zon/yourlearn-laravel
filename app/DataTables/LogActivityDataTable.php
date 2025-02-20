<?php

namespace App\DataTables;

use App\Models\LogActivity; 
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class LogActivityDataTable extends DataTable
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
     * @return EloquentDataTable
     */
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($logActivity) {
              //  return view('log_activity.action', compact('logActivity'));
            })
            ->setRowId('id')
            ->editColumn('created_at', function ($logActivity) {
                return $logActivity->created_at->format('d-m-Y H:i:s'); // Format the date
            })
            ->editColumn('event', function ($logActivity) {
                return ucfirst($logActivity->event); 
            })
            ->rawColumns(['action']);
    }

    public function query(LogActivity $model): QueryBuilder
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
        if (request()->filled('user_id')) {
            $userId = request()->input('user_id');
            $query->where('user_id', $userId);
        }
        return $query;
    }

    public function html(): HtmlBuilder{

        return $this->builder()
            ->setTableId('log-activity-table')
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
            ])->postAjax(route('log-activity.index'));
    }

   
    /**
     * Get the DataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('id')->width('10%'),
            Column::make('event')->title('Event')->width('40%'),
            Column::make('description')->title('History')->width('40%'),
            Column::make('created_at')->title('Date Changed')->width('20%'),
        ];

        return $columns;
    }

    /**
     * Get the filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'LogActivity_' . date('YmdHis');
    }
}

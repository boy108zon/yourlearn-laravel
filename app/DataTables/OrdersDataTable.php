<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrdersDataTable extends DataTable
{
    protected $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($order) {
                $userPermissions = $this->userPermissions;
                return view('orders.action', compact('order', 'userPermissions')); 
            })
            ->addColumn('customer_name', function ($order) {
                return $order->first_name . ' ' . $order->last_name;
            })
            ->addColumn('placed_by', function ($order) {
                if ($order->guest_id) {
                    return 'Guest';
                } elseif ($order->user_id) {
                    return 'User';
                }
                return 'Unknown';
            })
            ->editColumn('created_at', function ($order) {
                return $order->created_at->format('d-m-Y H:i:s');
            })
            ->editColumn('status', function ($order) {
                return $order->status;
            })
            ->editColumn('cart_status', function ($order) {
                return $order->cart->status;
            })
            ->setRowId('id')
            ->rawColumns(['action','placed_by','customer_name','cart_status','status']);
    }

   
    public function query(Order $model): QueryBuilder
    {
        $query = $model->newQuery();
        if (request()->filled('start_date') && request()->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse(request()->input('start_date'))->startOfDay(),
                Carbon::parse(request()->input('end_date'))->endOfDay()
            ]);
        }
        
        $query->with(['products', 'cart' => function ($query) {
            $query->where('status', '!=', 'pending')->orWhereNull('session_id');
        }]);
        
    
        return $query;
    }
    
    
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orders-table')
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
                'columnDefs' => [
                    [
                        'targets' => 0,  
                        'visible' => false,
                    ],
                ],
            ])->postAjax(route('orders.index'));
    }

    public function getColumns(): array
    {
        $canEdit = $this->userPermissions->contains('edit-order');
        $canDelete = $this->userPermissions->contains('remove-order');
        $canShow=$this->userPermissions->contains('show-order');

        $columns = [
            Column::make('id')->width('1%'),
            Column::make('customer_name')->title('Customer Name')->width('20%'),
            Column::make('email')->title('Email')->width('20%'),
            //Column::make('total_price')->title('Total Amount')->width('15%'),
            //Column::make('payment_method')->title('Pay Way')->width('10%'),
            //Column::make('tracking_number')->title('TRK No')->width('15%'),
           
            Column::computed('placed_by')->title('Placed By')->width('10%'),
            Column::make('created_at')->title('Order Date')->width('15%'),
            Column::make('status')->title('Status')->width('10%'),
            Column::make('cart_status')->title('Cart Status')->width('10%'),
                  
        ];

        if ($canEdit || $canDelete || $canShow) {

            $columns[] = Column::computed('action')
                ->exportable(true)
                ->printable(true)
                ->width('15%')
                ->addClass('text-center');
        }

        return $columns;
    }

    protected function filename(): string
    {
        return 'orders_' . date('YmdHis');
    }
}

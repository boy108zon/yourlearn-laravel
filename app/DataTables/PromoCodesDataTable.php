<?php

namespace App\DataTables;

use App\Models\PromoCodes;
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

class PromoCodesDataTable extends DataTable{
    
        protected $userPermissions;
    
        public function __construct()
        {
            $this->userPermissions = Auth::user()->availablePermissions()->pluck('slug');
        }
        private function calculateRemainingDays($startDate, $endDate)
        {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            return $endDate->diffInDays($startDate, true);
        }
        public function dataTable(QueryBuilder $query): EloquentDataTable
        {
            return (new EloquentDataTable($query))
                ->addColumn('action', function ($promocode) {
                    $userPermissions = $this->userPermissions;
                    return view('promo_codes.action', compact('promocode', 'userPermissions')); 
                })
                ->editColumn('code', function ($promocode) { 
                    $remainingDays = $this->calculateRemainingDays($promocode->start_date, $promocode->end_date);
                    $remainingDays =($remainingDays <= 0) ? 'Expired' : $remainingDays . ' days left';
                    $usageCountInOrders = PromoCodes::where('code', $promocode->code)
                    ->join('carts', 'carts.promo_code', '=', 'promo_codes.code')  
                    ->join('orders', 'orders.cart_id', '=', 'carts.id')           
                    ->count('orders.id');      
                    $popoverContent = view('promo_codes.popover_content', compact('promocode', 'remainingDays','usageCountInOrders'))->render();
                    
                    return "<a href='javascrpt:void(0);' class='link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover position-relative'
                            data-bs-toggle='popover' 
                            data-bs-trigger='focus' 
                            data-bs-title='Info: " . e($promocode->code) . "' 
                            data-bs-placement='right'
                            data-bs-content='".e($popoverContent)."'>
                            ".e(ucfirst($promocode->code))."
                        </a>";
                })
                ->editColumn('created_at', function ($promocode) {
                    return $promocode->created_at->format('d-m-Y H:i:s');
                })
                ->editColumn('start_date', function ($promocode) {
                    return $promocode->start_date->format('d-m-Y H:i:s');
                })
                ->editColumn('end_date', function ($promocode) {
                    return $promocode->end_date->format('d-m-Y H:i:s');
                })
                ->editColumn('discount_type', function ($promocode) {
                    return ucfirst($promocode->discount_type);
                })
                ->editColumn('is_active', function ($promocode) {
                    $badgeClass = $promocode->is_active == 1 ? 'badge text-bg-primary rounded-pill' : 'badge text-bg-danger rounded-pill';
                    $statusText = $promocode->is_active == 1 ? 'Active' : 'Inactive';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($statusText) . '</span>';
                })
                ->setRowId('id')
                ->rawColumns(['action', 'is_active','code']);
        }

        
        public function query(PromoCodes $model): QueryBuilder
        {
            $query = $model->newQuery();
            if (request()->filled('start_date') && request()->filled('end_date')) {
                $query->whereBetween('created_at', [
                    Carbon::parse(request()->input('start_date'))->startOfDay(),
                    Carbon::parse(request()->input('end_date'))->endOfDay()
                ]);
            }
            
            $query->with('products');
            
        
            return $query;
        }
        
        
        public function html(): HtmlBuilder
        {
            return $this->builder()
                ->setTableId('promocodes-table')
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
                ])->postAjax(route('promocodes.index'));
        }
    
        public function getColumns(): array
        {
            $canEdit = $this->userPermissions->contains('edit-promo-code');
            $canDelete = $this->userPermissions->contains('remove-promo-code');
            $canCreate = $this->userPermissions->contains('create-promo-code');

            $columns = [
                Column::make('id')->width('1%'),
                Column::make('code')->width('10%'),
                Column::make('discount_type')->title('Type')->width('10%'),
                
                Column::computed('discount_amount')->title('Discount')->width('5%'),
                Column::make('start_date')->title('Start Date')->width('15%'),
                Column::make('end_date')->title('End Date')->width('15%'),
                Column::make('is_active')->title('Status')->width('10%'),
                
            ];
    
            if ($canEdit || $canDelete || $canCreate) {
    
                $columns[] = Column::computed('action')->title('Actions')->width('10%')->addClass('text-center')->exportable(false)->searchable(false)->printable(false);
            }
    
            return $columns;
        }
    
        protected function filename(): string
        {
            return 'promocodes' . date('YmdHis');
        }
    }
    
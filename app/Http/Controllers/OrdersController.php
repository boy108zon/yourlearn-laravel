<?php

namespace App\Http\Controllers;

use App\DataTables\OrdersDataTable;
use App\Models\Order; 
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrdersActionService;

class OrdersController extends Controller
{
    protected $OrdersActionService;

    public function __construct(OrdersActionService $OrdersActionService)
    {
        $this->middleware('auth');
        $this->OrdersActionService = $OrdersActionService;
    }

    public function index(OrdersDataTable $dataTable)
    {
        $user = auth()->user();
        $actions = $this->OrdersActionService->getActions($user);
        return $dataTable->render('orders.index', compact('actions'));
    }

    public function create()
    {
        return view('orders.create');
    }

   
    public function store(CreateOrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create($validated);
        if ($request->has('products')) {
            $order->products()->attach($request->products); 
        }

        return redirect()->route('orders.index')->with('swal', [
            'message' => 'Order created successfully!',
            'type' => 'success',
        ]);
    }

   
    public function edit(Order $order)
    {
        $order = $order->with([
            'products',
            'cart' => function ($query) {
                $query->orWhereNull('session_id');
            }
        ])->find($order->id);
        
        return view('orders.edit', compact('order'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();
        $order->update($validated); 

        if ($request->has('order_products')) {
            $orderProducts = $request->input('order_products');

            foreach ($orderProducts as $productId => $data) {
                $order->products()->updateExistingPivot($productId, [
                    'quantity' => $data['quantity'],  
                ]);
            }
        }

        return redirect()->route('orders.index')->with('swal', [
            'message' => 'Order updated successfully!',
            'type' => 'success',
        ]);
    }


    public function show(Order $order)
    {
        $order = $order->with([
            'products',
            'cart' => function ($query) {
                $query->orWhereNull('session_id');
            }
        ])->find($order->id);
        
        return view('orders.show', compact('order'));  
    }

    public function destroy($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            
            $order->products()->detach();
            if ($order->cart) {
                $order->cart->delete();
            }

            $order->delete();
            
            return redirect()->route('orders.index')->with('swal', [
                'message' => 'Order deleted permanently!',
                'type' => 'success',
            ]);
        }

        return redirect()->route('orders.index')->with('swal', [
            'message' => 'Order not found.',
            'type' => 'error',
        ]);
    }

    private function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }
}

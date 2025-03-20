<?php

namespace App\Http\Controllers;

use App\DataTables\PromoCodesDataTable;
use App\Models\PromoCodes; 
use App\Models\Product;
use App\Http\Requests\CreatePromoCodesRequest;
use App\Http\Requests\UpdatePromoCodesRequest;
use App\Services\PromoCodesActionService;
use Carbon\Carbon;

class PromoCodesController extends Controller
{
    protected $PromoCodesActionService;

    public function __construct(PromoCodesActionService $PromoCodesActionService)
    {
        $this->middleware('auth');
        $this->PromoCodesActionService = $PromoCodesActionService;
    }

    public function index(PromoCodesDataTable $dataTable)
    {
        $user = auth()->user();
        $actions = $this->PromoCodesActionService->getActions($user);
        return $dataTable->render('promo_codes.index', compact('actions'));
    }
    public function create()
    {
        $products = Product::select('id', 'name')->get();
        return view('promo_codes.create', compact('products'));
    }

    public function store(CreatePromoCodesRequest $request)
    {
        $validated = $request->validated();

        if ($request->has('start_date')) {
            $validated['start_date'] = Carbon::parse($request->start_date);
        }

        if ($request->has('end_date')) {
            $validated['end_date'] = Carbon::parse($request->end_date);
        }

        $promoCode = PromoCodes::create($validated);
        if ($request->has('products')) {
            $promoCode->products()->sync($request->products); 
        }

        return redirect()->route('promocodes.index')->with('swal', [
            'message' => 'Promo code created successfully!',
            'type' => 'success',
        ]);
    }
    
    public function edit(PromoCodes $promoCode)
    {
       
        $products = Product::select('id', 'name')->get();
        return view('promo_codes.edit', compact('promoCode', 'products'));
    }

    public function update(UpdatePromoCodesRequest $request, PromoCodes $promoCode){
    
        $validated = $request->validated();

        if ($request->has('start_date')) {
            $validated['start_date'] = Carbon::parse($request->start_date);
        }

        if ($request->has('end_date')) {
            $validated['end_date'] = Carbon::parse($request->end_date);
        }

        $promoCode->update($validated);

        if ($request->has('products')) {
            $promoCode->products()->sync($request->products);
        }

        return redirect()->route('promocodes.index')->with('swal', [
            'message' => 'Promo code saved successfully!',
            'type' => 'success',
        ]);
    }


    public function destroy(PromoCodes $promoCode)
    {
     
        $promoCode->delete();
        return redirect()->route('promocodes.index')->with('success', 'Promo Code deleted successfully!');
    }

}

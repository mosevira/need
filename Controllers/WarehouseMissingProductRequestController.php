<?php

namespace App\Http\Controllers;

use App\Models\WarehouseMissingProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WarehouseMissingProductRequestController extends Controller
{
    public function create()
    {
        $branchId = Session::get('branch_id');
        $products = Product::where('branch_id', $branchId)->get();
        return view('storekeeper.warehouse_missing_product_request.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'requested_quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $branchId = Session::get('branch_id');

        WarehouseMissingProductRequest::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'requested_quantity' => $request->requested_quantity,
            'reason' => $request->reason,
            'branch_id' => $branchId,
        ]);

        return redirect()->route('storekeeper.dashboard')->with('success', 'Заявка на недостающий товар на складе создана.');
    }
    public function show(string $id): View
    {
        $request = WarehouseMissingProductRequest::findOrFail($id); // Получаем заявку по ID или выбрасываем исключение 404

        return view('storekeeper.warehouse_requests.show', compact('request')); // Передаем заявку в представление
    }
}


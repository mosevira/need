<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MissingProductRequest;

class MissingProductRequestController extends Controller
{
    public function create()
    {
        $products = Product::all(); // Здесь нужно добавить фильтрацию по филиалу
        return view('seller.missing_product_request.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'requested_quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        MissingProductRequest::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'requested_quantity' => $request->requested_quantity,
            'reason' => $request->reason,
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Заявка создана.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StockController extends Controller
{
    public function index()
    {
        $branchId = Session::get('branch_id');
        $products = Product::where('branch_id', $branchId)->get();
        $productsWithQuantity = $products->map(function ($product) use ($branchId) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->first();

            $product->quantity = $inventory ? $inventory->quantity : 0; // Добавляем поле quantity

            return $product;
        });
        return view('storekeeper.stock.index', compact('productsWithQuantity'));
    }

    public function create()
    {
        return view('storekeeper.stock.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $branchId = Session::get('branch_id');
        $product = new Product($request->all());
        $product->branch_id = $branchId;
        $product->save();

        //Добавим запись о количестве товара
        Inventory::create([
           'branch_id' => $branchId,
           'product_id' => $product->id,
           'quantity' => $request->quantity,
        ]);

        return redirect()->route('storekeeper.stock.index')->with('success', 'Товар создан успешно.');
    }
}

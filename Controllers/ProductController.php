<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WriteOff;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index()
    {
        $branchId = Session::get('branch_id');
        $products = Product::where('branch_id', $branchId)->get();

        // Получаем количество товара для каждого продукта
        $productsWithQuantity = $products->map(function ($product) use ($branchId) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->first();

            $product->quantity = $inventory ? $inventory->quantity : 0; // Добавляем поле quantity

            return $product;
        });

        return view('seller.products.index', compact('productsWithQuantity'));
    }

    public function updateQuantity(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $branchId = Session::get('branch_id');

        $inventory = Inventory::where('branch_id', $branchId)
            ->where('product_id', $product->id)
            ->firstOrFail();

        $inventory->quantity = $request->quantity;
        $inventory->save();

        return redirect()->route('seller.products.index')->with('success', 'Количество товара обновлено.');
    }


    public function show(Product $product)
    {
        $inventory = Inventory::where('product_id', $product->id)
            ->get()
            ->groupBy('branch_id');

        $branches = Branch::all(); // Получаем все филиалы

        return view('seller.products.show', compact('product', 'inventory', 'branches'));
    }

        public function writeOff(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $branchId = Session::get('branch_id');

        $inventory = Inventory::where('branch_id', $branchId)
            ->where('product_id', $product->id)
            ->firstOrFail();

        if ($inventory->quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Недостаточно товара на складе.']);
        }

        // Создаем запись о списании
        WriteOff::create([
            'branch_id' => $branchId,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'user_id' => Auth::id(), // Кто списал товар
        ]);

        $inventory->quantity -= $request->quantity;
        $inventory->save();

        return redirect()->route('seller.products.details', $product->id)->with('success', 'Товар списан.');
    }

    public function details(Product $product)
    {
        $branchId = Session::get('branch_id');

        $inventory = Inventory::where('product_id', $product->id)
            ->get()
            ->groupBy('branch_id');

        $branches = Branch::all();

        $quantity = Inventory::where('branch_id', $branchId)
            ->where('product_id', $product->id)
            ->first()
            ->quantity ?? 0;

        return view('seller.products.details', compact('product', 'inventory', 'branches', 'quantity'));
    }
    public function scan(Request $request)
{
    return view('products.scan');
}

public function processScan(Request $request)
{
    $request->validate(['barcode' => 'required|string']);

    $product = Product::where('barcode', $request->barcode)->first();

    if (!$product) {
        return response()->json([
            'error' => 'Товар не найден',
            'action' => 'add' // Кнопка "Добавить новый товар"
        ], 404);
    }

    return response()->json([
        'product' => $product,
        'html' => view('products.partials.scan-result', compact('product'))->render()
    ]);
}

public function findByBarcode($barcode)
{
    $product = Product::where('barcode', $barcode)->first();

    if (!$product) {
        return response()->json([
            'error' => 'Товар не найден',
            'action' => 'create'
        ], 404);
    }

    return response()->json($product);
}
}

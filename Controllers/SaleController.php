<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::where('user_id', Auth::id())->with('saleItems.product')->get();
        return view('seller.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all(); // Получаем все товары
        return view('seller.sales.create', compact('products'));
    }
     public function show()
    {
        $products = Product::all(); // Получаем все товары
        
        return view('seller.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array', // Проверяем массив товаров
            'items.*.product_id' => 'required|exists:products,id', // Проверяем каждый товар
            'items.*.quantity' => 'required|integer|min:1', // Проверяем количество
        ]);

        $sale = Sale::create(['user_id' => Auth::id()]);

        $totalAmount = 0; // Инициализируем сумму

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            if ($product->quantity < $item['quantity']) {
                return redirect()->back()->withErrors(['items' => "Недостаточно товара {$product->name} на складе."]);
            }

            // Создаем запись о продаже
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            // Увеличиваем общую сумму
            $totalAmount += $product->price * $item['quantity'];

            // Уменьшаем количество товара на складе
            $product->decrement('quantity', $item['quantity']);
        }

        // Обновляем общую сумму в объекте Sale
        $sale->update(['total_amount' => $totalAmount]);

        return redirect()->route('sales.index')->with('success', 'Продажа успешно создана!');
    }
}

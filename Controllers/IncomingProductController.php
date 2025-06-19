<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IncomingProductController extends Controller
{
    public function create()
    {
        return view('storekeeper.incoming_product.create');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $branchId = Session::get('branch_id');

        // TODO: Implement barcode lookup, for now just search by product ID
        $product = Product::find($request->barcode);

        if ($product) {
            return response()->json([
                'product' => $product,
                'exists' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'Товар отсутствует в базе данных',
                'exists' => false,
            ]);
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Store product and quantity in session
        $incomingProducts = session('incoming_products', []);
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $incomingProducts[$productId] = $quantity;
        session(['incoming_products' => $incomingProducts]);

        return response()->json([
            'success' => true,
            'message' => 'Товар добавлен',
        ]);
    }

    public function store(Request $request)
    {
        $branchId = Session::get('branch_id');
        $incomingProducts = session('incoming_products', []);

        foreach ($incomingProducts as $productId => $quantity) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->first();

            if ($inventory) {
                $inventory->quantity += $quantity;
                $inventory->save();
            } else {
                Inventory::create([
                    'branch_id' => $branchId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }

        // Clear session
        session()->forget('incoming_products');

        return redirect()->route('storekeeper.dashboard')->with('success', 'Товар успешно принят.');
    }

        public function load()
    {
        $incomingProducts = session('incoming_products', []);
        $html = '';

        foreach ($incomingProducts as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $html .= '<tr>';
                $html .= '<td>' . $product->name . '</td>';
                $html .= '<td>' . $quantity . '</td>';
                $html .= '<td><button class="remove-button" data-product-id="' . $productId . '">Удалить</button></td>';
                $html .= '</tr>';
            }
        }

        return $html;
    }


        public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $incomingProducts = session('incoming_products', []);

        if (isset($incomingProducts[$productId])) {
            unset($incomingProducts[$productId]);
            session(['incoming_products' => $incomingProducts]);

            return response()->json([
                'success' => true,
                'message' => 'Товар удален',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Товар не найден в списке',
        ]);
    }
}

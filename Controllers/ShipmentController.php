<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\Inventory;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShipmentController extends Controller
{
    public function index()
    {
        $branchId = Session::get('branch_id'); // Получаем branch_id из сессии
        $shipments = Shipment::where('from_branch_id', $branchId)->get(); // Запрос в базу данных
        return view('storekeeper.shipments.index', compact('shipments')); // Передаем данные в представление
    }

    public function create(Request $request)
    {
        $branchId = Session::get('branch_id');
        $branches = Branch::where('id', '!=', $branchId)->get(); // Исключаем текущий филиал
        $products = Product::where('branch_id', $branchId)->get();
        $isIncoming = $request->query('incoming', false); // Получаем параметр из URL

        return view('storekeeper.shipments.create', compact('branches', 'products', 'isIncoming'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_branch_id' => 'required|exists:branches,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'is_incoming' => 'nullable|boolean',
        ]);

        $branchId = Session::get('branch_id');

        $shipment = Shipment::create([
            'from_branch_id' => $branchId,
            'to_branch_id' => $request->to_branch_id,
            'created_by' => Auth::id(),
            'is_incoming' => $request->input('is_incoming', false),
        ]);

        foreach ($request->products as $productData) {
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
            ]);

            // Увеличиваем количество товара на складе, если это входящая накладная
            if ($shipment->is_incoming) {
                $inventory = Inventory::where('branch_id', $branchId)
                    ->where('product_id', $productData['product_id'])
                    ->first();

                if ($inventory) {
                    $inventory->quantity += $productData['quantity'];
                    $inventory->save();
                } else {
                    Inventory::create([
                        'branch_id' => $branchId,
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'],
                    ]);
                }
            }
        }

        return redirect()->route('storekeeper.shipments.index')->with('success', 'Накладная создана.');
    }

    public function show(Shipment $shipment)
    {
        return view('storekeeper.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $branchId = Session::get('branch_id');
        $branches = Branch::where('id', '!=', $branchId)->get(); // Исключаем текущий филиал
        $products = Product::where('branch_id', $branchId)->get();

        return view('storekeeper.shipments.edit', compact('shipment', 'branches', 'products'));
    }

        public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'to_branch_id' => 'required|exists:branches,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'is_incoming' => 'nullable|boolean',
        ]);

        $branchId = Session::get('branch_id');

        // 1. Отменяем изменения, внесенные предыдущей версией накладной

        // Если накладная была входящей, уменьшаем количество товара на складе
        if ($shipment->is_incoming) {
            foreach ($shipment->items as $item) {
                $inventory = Inventory::where('branch_id', $branchId)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($inventory) {
                    $inventory->quantity -= $item->quantity;
                    $inventory->save();
                }
            }
        }

        // 2. Применяем новые изменения

        // Удаляем старые товары из накладной
        ShipmentItem::where('shipment_id', $shipment->id)->delete();

        // Обновляем значения накладной
        $shipment->to_branch_id = $request->to_branch_id;
        $shipment->is_incoming = $request->input('is_incoming', false); // Обновляем значение is_incoming
        $shipment->save();

        // Добавляем новые товары в накладную
        foreach ($request->products as $productData) {
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
            ]);

            // Увеличиваем количество товара на складе, если это входящая накладная
            if ($shipment->is_incoming) {
                $inventory = Inventory::where('branch_id', $branchId)
                    ->where('product_id', $productData['product_id'])
                    ->first();

                if ($inventory) {
                    $inventory->quantity += $productData['quantity'];
                    $inventory->save();
                } else {
                    Inventory::create([
                        'branch_id' => $branchId,
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'],
                    ]);
                }
            }
        }

        return redirect()->route('storekeeper.shipments.index')->with('success', 'Накладная обновлена.');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()->route('storekeeper.shipments.index')->with('success', 'Накладная удалена.');
    }
}

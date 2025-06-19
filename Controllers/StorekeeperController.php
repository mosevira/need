<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\MissingProductRequest;
use Illuminate\Support\Facades\Session;
use App\Models\WarehouseMissingProductRequest;

class StorekeeperController extends Controller
{
    public function dashboard()
    {
        $branchId = Session::get('branch_id');

        // Последние заявки из магазинов
        $latestRequests = MissingProductRequest::latest()->limit(5)->get();

        // Список кончающегося товара на складе
        $lowStockWarehouse = Product::where('branch_id', $branchId)->get();
        $lowStockWarehouse = $lowStockWarehouse->filter(function ($product) use ($branchId) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->first();

            return $inventory && $inventory->quantity < 10;
        });


        // Список кончающегося товара в магазинах
        $lowStockStores = Product::where('branch_id', $branchId)->get();
        $lowStockStores = $lowStockStores->filter(function ($product) use ($branchId) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->first();

            return $inventory && $inventory->quantity < 10;
        });

        return view('storekeeper.dashboard', compact('latestRequests', 'lowStockWarehouse', 'lowStockStores'));
    }

        public function requests()
    {
        $requests = MissingProductRequest::with('product', 'user')->get();

        return view('storekeeper.requests.index', compact('requests'));
    }

        public function branches()
    {
        $branches = Branch::all();

        return view('storekeeper.branches.index', compact('branches'));
    }

        public function products($branchId)
    {
        $products = Product::where('branch_id', $branchId)->get();

        // Получаем количество товара для каждого продукта
        $productsWithQuantity = $products->map(function ($product) use ($branchId) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->first();

            $product->quantity = $inventory ? $inventory->quantity : 0; // Добавляем поле quantity

            return $product;
        });

        $branch = Branch::findOrFail($branchId); // Получаем информацию о филиале

        return view('storekeeper.branches.products', compact('productsWithQuantity', 'branch'));
    }

        public function showRequest(MissingProductRequest $missingProductRequest)
    {
        return view('storekeeper.requests.show', compact('missingProductRequest'));
    }


        public function updateRequestStatus(Request $request, MissingProductRequest $missingProductRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed,completed',
        ]);

        $missingProductRequest->status = $request->status;
        $missingProductRequest->save();

        return redirect()->route('storekeeper.dashboard')->with('success', 'Статус заявки обновлен.');
    }

    

        public function showWarehouseRequest(WarehouseMissingProductRequest $warehouseMissingProductRequest)
    {
        return view('storekeeper.warehouse_requests.show', compact('warehouseMissingProductRequest'));
    }



    public function updateWarehouseRequestStatus(Request $request, WarehouseMissingProductRequest $warehouseMissingProductRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed,completed',
        ]);

        $warehouseMissingProductRequest->status = $request->status;
        $warehouseMissingProductRequest->save();

        return redirect()->route('storekeeper.dashboard')->with('success', 'Статус заявки на складе обновлен.');
    }

}

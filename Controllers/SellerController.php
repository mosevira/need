<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SellerController extends Controller
{
    public function dashboard()
    {
        $branchId = Session::get('branch_id');
        $pendingInvoices = Invoice::where('branch_id', $branchId)
            ->where('status', 'pending')
            ->get();

        $lowStockProducts = Product::where('branch_id', $branchId)->get();
        $lowStockProducts = $lowStockProducts->filter(function ($product) use ($branchId) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $product->id)
                ->first();

            return $inventory && $inventory->quantity < 10;
        });
        $productsCount = Product::count();
        $totalPrice = Product::sum('price'); // Сумма всех цен
        $products = Product::all(); // Все продукты для графиков (если нужно)


        return view('seller.dashboard', compact('pendingInvoices', 'lowStockProducts','productsCount', 'totalPrice', 'products'));
    }

    public function acceptInvoice(Invoice $invoice)
    {
        $invoice->status = 'accepted';
        $invoice->save();

        // Увеличиваем количество товара на складе
        foreach ($invoice->items as $item) {
            $inventory = Inventory::where('branch_id', $invoice->branch_id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($inventory) {
                $inventory->quantity += $item->quantity;
                $inventory->save();
            } else {
                // Если товара нет на складе, создаем новую запись
                Inventory::create([
                    'branch_id' => $invoice->branch_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);
            }
        }

        return redirect()->route('seller.dashboard')->with('success', 'Накладная принята.');
    }



    public function rejectInvoice(Invoice $invoice)
    {
        $invoice->status = 'rejected';
        $invoice->save();
        return redirect()->route('seller.dashboard')->with('success', 'Накладная отклонена.');
    }

}

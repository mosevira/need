<?php

namespace App\Http\Controllers;

use App\Models\Consignment;
use App\Models\ConsignmentItem;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConsignmentController extends Controller
{
    public function index()
    {
        $consignments = Consignment::with('fromBranch', 'toBranch', 'createdBy')->get();
        return view('storekeeper.consignments.index', compact('consignments'));
    }

    public function create()
    {
        $branchId = Session::get('branch_id');
        $branches = Branch::where('id', '!=', $branchId)->get(); // Исключаем текущий филиал
        $products = Product::where('branch_id', $branchId)->get(); // Товары текущего филиала

        return view('storekeeper.consignments.create', compact('branches', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity.*' => 'integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $branchId = Session::get('branch_id');

        $consignment = Consignment::create([
            'from_branch_id' => $branchId,
            'to_branch_id' => $request->to_branch_id,
            'created_by' => Auth::id(),
            'notes' => $request->notes,
        ]);

        foreach ($request->product_id as $key => $productId) {
            ConsignmentItem::create([
                'consignment_id' => $consignment->id,
                'product_id' => $productId,
                'quantity' => $request->quantity[$key],
            ]);
        }

        return redirect()->route('storekeeper.consignments.index')->with('success', 'Накладная создана.');
    }

    public function show(Consignment $consignment)
    {
        $consignment->load('items.product', 'fromBranch', 'toBranch', 'createdBy');
        return view('storekeeper.consignments.show', compact('consignment'));
    }

    public function edit(Consignment $consignment)
    {
        $branchId = Session::get('branch_id');
        $branches = Branch::where('id', '!=', $branchId)->get(); // Исключаем текущий филиал
        $products = Product::where('branch_id', $branchId)->get(); // Товары текущего филиала

        $consignment->load('items');

        return view('storekeeper.consignments.edit', compact('consignment', 'branches', 'products'));
    }

    public function update(Request $request, Consignment $consignment)
    {
        $request->validate([
            'to_branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity.*' => 'integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Удаляем существующие элементы накладной
        ConsignmentItem::where('consignment_id', $consignment->id)->delete();

        // Создаем новые элементы накладной
        foreach ($request->product_id as $key => $productId) {
            ConsignmentItem::create([
                'consignment_id' => $consignment->id,
                'product_id' => $productId,
                'quantity' => $request->quantity[$key],
            ]);
        }

        $consignment->update([
            'to_branch_id' => $request->to_branch_id,
            'notes' => $request->notes,
        ]);

        return redirect()->route('storekeeper.consignments.index')->with('success', 'Накладная обновлена.');
    }

    public function destroy(Consignment $consignment)
    {
        $consignment->delete();
        return redirect()->route('storekeeper.consignments.index')->with('success', 'Накладная удалена.');
    }
}

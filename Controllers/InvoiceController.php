<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['branch', 'creator'])
            ->when(Auth::user()->role !== 'admin', fn($q) => $q->where('branch_id', Auth::id()))
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

public function create()
{
    $user = auth()->user();
    $branches = $user->isAdmin()
        ? Branch::all()
        : Branch::where('id', $user->branch_id)->get();

    $products = $user->isAdmin()
        ? Product::all()
        : Product::where('branch_id', $user->branch_id)->get();

    return view('invoices.create', compact('branches', 'products'));
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => ['required', Rule::exists('branches', 'id')],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1']
        ]);

        $invoice = DB::transaction(function () use ($validated) {
            $invoice = Invoice::create([
                'number' => 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'branch_id' => $validated['branch_id'],
                'status' => Invoice::STATUS_PROCESSING,
                'created_by' => Auth::id(),
                'date' => now()
            ]);

            $invoice->items()->createMany($validated['items']);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return view('invoices.show', [
            'invoice' => $invoice->load(['items.product', 'branch', 'creator'])
        ]);
    }

    public function accept(Invoice $invoice)
    {
        $this->authorize('accept', $invoice);

        return view('invoices.accept', [
            'invoice' => $invoice->load(['items.product'])
        ]);
    }

    public function processAccept(Request $request, Invoice $invoice)
    {
        $this->authorize('accept', $invoice);

        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', Rule::exists('invoice_items', 'id')->where('invoice_id', $invoice->id)],
            'items.*.accepted_quantity' => ['required', 'integer', 'min:0']
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            collect($validated['items'])->each(function ($item) {
                InvoiceItem::find($item['id'])->update([
                    'accepted_quantity' => $item['accepted_quantity']
                ]);
            });

            $invoice->update([
                'status' => $this->determineFinalStatus($invoice),
                'accepted_by' => Auth::id()
            ]);
        });

        return redirect()->route('invoices.show', $invoice);
    }

    public function close(Invoice $invoice)
    {
        $this->authorize('close', $invoice);

        $invoice->close();

        return back()->with('success', 'Накладная закрыта');
    }

    protected function determineFinalStatus(Invoice $invoice): string
    {
        $items = $invoice->items;

        if ($items->whereNull('accepted_quantity')->count() > 0) {
            return Invoice::STATUS_IN_STORE;
        }

        return $items->where('accepted_quantity', '!=', 'quantity')->count() > 0
            ? Invoice::STATUS_DISCREPANCY
            : Invoice::STATUS_ACCEPTED;
    }

}

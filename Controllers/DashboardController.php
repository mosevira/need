<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
   public function admin()
    {
        return view('dashboard.admin', [
            'users' => User::where('is_active', true)->count(),
            'branches' => Branch::count(),
            'lowStockProducts' => Product::where('quantity', '<', 5)->count(),
            'pendingInvoices' => Invoice::where('status', 'processing')->count()
        ]);
    }

    public function storekeeper()
{
    return view('dashboard.storekeeper', [
        'lowStockWarehouse' => Product::where('quantity', '<', 5)->count(),
        'pendingRequests' => Invoice::where('status', 'processing')->count(),
        'recentInvoices' => Invoice::latest()->take(5)->get()
    ]);
}
}

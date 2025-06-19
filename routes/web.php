<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Models\WarehouseMissingProductRequest;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\StorekeeperController;
use App\Http\Controllers\IncomingProductController;
use App\Http\Controllers\MissingProductRequestController;
use App\Http\Controllers\WarehouseMissingProductRequestController;
use App\Http\Controllers\Admin\AdminUserController; // Ensure this is present
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('admin/dashboard', [AdminController::class, 'dashboard'])
//     ->name('admin.dashboard')
//     ->middleware(['auth', 'checkRole:admin']);

    Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::patch('users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('admin.users.toggleActive');
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('products', ProductController::class);
        Route::resource('branches', BranchController::class)->except(['show']);
        Route::resource('invoices', InvoiceController::class)->except(['edit', 'update', 'destroy']);
Route::post('invoices/{invoice}/items/{item}/confirm', [InvoiceController::class, 'confirmItem'])->name('invoices.items.confirm');
    });

Route::post('incoming-product/remove', [IncomingProductController::class, 'remove'])->name('storekeeper.incoming_product.remove');
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {



    Route::middleware(['auth', 'checkRole:storekeeper', 'log.storekeeper.activity'])->prefix('storekeeper')->group(function () {

        Route::get('/dashboard', [StorekeeperController::class, 'dashboard'])->name('storekeeper.dashboard');
        Route::get('warehouse-missing-product-request/create', [WarehouseMissingProductRequestController::class, 'create'])->name('storekeeper.warehouse_missing_product_request.create');
        Route::post('warehouse-missing-product-request', [WarehouseMissingProductRequestController::class, 'store'])->name('storekeeper.warehouse_missing_product_request.store');
        Route::get('requests', [StorekeeperController::class, 'requests'])->name('storekeeper.requests.index');
        Route::get('branches', [StorekeeperController::class, 'branches'])->name('storekeeper.branches.index');
        Route::get('branches/{branchId}/products', [StorekeeperController::class, 'products'])->name('storekeeper.branches.products');
        Route::resource('stock', StockController::class);
        Route::resource('shipments', ShipmentController::class);

        Route::get('incoming-product/create', [IncomingProductController::class, 'create'])->name('storekeeper.incoming_product.create');
        Route::post('incoming-product/scan', [IncomingProductController::class, 'scan'])->name('storekeeper.incoming_product.scan');
        Route::post('incoming-product/add', [IncomingProductController::class, 'add'])->name('storekeeper.incoming_product.add');
        Route::get('incoming-product/load', [IncomingProductController::class, 'load'])->name('storekeeper.incoming_product.load');
        Route::post('incoming-product/store', [IncomingProductController::class, 'store'])->name('storekeeper.incoming_product.store');

        Route::get('requests/{missingProductRequest}', [StorekeeperController::class, 'showRequest'])->name('storekeeper.requests.show');
        Route::put('requests/{missingProductRequest}/update-status', [StorekeeperController::class, 'updateRequestStatus'])->name('storekeeper.requests.updateStatus');

        Route::get('warehouse-requests/{warehouseMissingProductRequest}', [StorekeeperController::class, 'showWarehouseRequest'])->name('storekeeper.warehouse_requests.show');
        Route::put('warehouse-requests/{warehouseMissingProductRequest}/update-status', [StorekeeperController::class, 'updateWarehouseRequestStatus'])->name('storekeeper.warehouse_requests.updateStatus');

        Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');

    });

    Route::middleware(['auth', 'checkRole:seller'])->prefix('seller')->group(function () {



        Route::get('dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard');
        Route::get('products', [ProductController::class, 'index'])->name('seller.products.index');
        Route::get('products', [ProductController::class, 'index'])->name('seller.products.index');
        Route::get('missing-product-request/create', [MissingProductRequestController::class, 'create'])->name('seller.missing_product.create');
        Route::post('missing-product-request', [MissingProductRequestController::class, 'store'])->name('seller.missing_product.store');
        Route::put('invoice/{invoice}/accept', [SellerController::class, 'acceptInvoice'])->name('seller.invoice.accept');
        Route::put('invoice/{invoice}/reject', [SellerController::class, 'rejectInvoice'])->name('seller.invoice.reject');
        Route::put('products/{product}/update-quantity', [ProductController::class, 'updateQuantity'])->name('seller.products.updateQuantity');

        Route::post('products/{product}/write-off', [ProductController::class, 'writeOff'])->name('seller.products.writeOff');
        Route::get('products/{product}/details', [ProductController::class, 'details'])->name('seller.products.details');
        //Route::get('products/{product}', [ProductController::class, 'show'])->name('seller.products.show'); // Удаляем старый маршрут
        Route::post('products/{product}/write-off', [ProductController::class, 'writeOff'])->name('seller.products.writeOff');
        Route::put('products/{product}/update-quantity', [ProductController::class, 'updateQuantity'])->name('seller.products.updateQuantity');


        Route::post('invoices/{invoice}/items/{item}/confirm', [InvoiceController::class, 'confirmItem'])->name('invoices.items.confirm');
        Route::get('/invoices', [InvoiceController::class, 'indexForSeller'])->name('invoices.indexForSeller');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'showForSeller'])->name('invoices.showForSeller');
        Route::put('/invoices/{invoice}/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');
        Route::put('/invoices/{invoice}/reject', [InvoiceController::class, 'reject'])->name('invoices.reject');
        // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');




    });

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('storekeeper.activity_logs.index');


});
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('/test', function () {
    return view('test');
})->name('test');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
         ->middleware('role:admin')
         ->name('dashboard.admin');

    Route::get('/dashboard/storekeeper', [DashboardController::class, 'storekeeper'])
         ->middleware('role:storekeeper')
         ->name('dashboard.storekeeper');
});

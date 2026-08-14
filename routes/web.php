<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\InventoryAdjustmentController;
use App\Http\Controllers\WasteLogController;
use App\Http\Controllers\StockCountController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DeliveryZoneController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LoyaltyCampaignController;
use App\Http\Controllers\NotificationCampaignController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\QRMenuController;
use App\Http\Controllers\CommandPaletteController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::middleware(['auth'])->group(function () {

    // Super Admin Routes
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
        Route::resource('tenants', TenantController::class);
        Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
        Route::resource('support-tickets', SupportTicketController::class)->except(['edit', 'update', 'destroy']);
        Route::patch('/support-tickets/{supportTicket}/status', [SupportTicketController::class, 'updateStatus'])->name('support-tickets.update-status');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS
    Route::get('/pos', [OrderController::class, 'pos'])->name('pos.index');
    Route::post('/pos/cart/add', [OrderController::class, 'cartAdd'])->name('pos.cart.add');
    Route::post('/pos/cart/update', [OrderController::class, 'cartUpdate'])->name('pos.cart.update');
    Route::post('/pos/cart/remove', [OrderController::class, 'cartRemove'])->name('pos.cart.remove');
    Route::post('/pos/cart/clear', [OrderController::class, 'cartClear'])->name('pos.cart.clear');
    Route::post('/pos/order', [OrderController::class, 'store'])->name('pos.order.store');
    Route::post('/pos/hold', [OrderController::class, 'holdOrder'])->name('pos.hold');
    Route::post('/pos/resume/{heldOrder}', [OrderController::class, 'resumeOrder'])->name('pos.resume');
    Route::delete('/pos/held/{heldOrder}', [OrderController::class, 'deleteHeldOrder'])->name('pos.held.delete');
    Route::post('/pos/coupon/apply', [OrderController::class, 'applyCoupon'])->name('pos.coupon.apply');
    Route::post('/pos/coupon/remove', [OrderController::class, 'removeCoupon'])->name('pos.coupon.remove');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/refund', [OrderController::class, 'refund'])->name('orders.refund');
    Route::post('/orders/{order}/repeat', [OrderController::class, 'repeatOrder'])->name('orders.repeat');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Menu Items
    Route::resource('menu', MenuItemController::class)->except(['show']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Tables
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::get('/tables/floor', [TableController::class, 'floor'])->name('tables.floor');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::put('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
    Route::put('/tables/{table}/position', [TableController::class, 'updatePosition'])->name('tables.position');
    Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
    Route::patch('/tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.status');

    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Waitlist
    Route::get('/waitlist', [WaitlistController::class, 'index'])->name('waitlist.index');
    Route::post('/waitlist', [WaitlistController::class, 'store'])->name('waitlist.store');
    Route::put('/waitlist/{waitlist}', [WaitlistController::class, 'update'])->name('waitlist.update');
    Route::delete('/waitlist/{waitlist}', [WaitlistController::class, 'destroy'])->name('waitlist.destroy');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Kitchen Display System (KDS)
    Route::get('/kitchen', [\App\Http\Controllers\KitchenController::class, 'index'])->name('kitchen.index');
    Route::patch('/kitchen/{order}', [\App\Http\Controllers\KitchenController::class, 'updateStatus'])->name('kitchen.updateStatus');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    // Suppliers (Module 10)
    Route::resource('suppliers', SupplierController::class);
    Route::post('/suppliers/{supplier}/payment', [SupplierController::class, 'recordPayment'])->name('suppliers.payment');

    // Purchase Orders (Module 10)
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::patch('/purchase-orders/{purchaseOrder}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.status');
    Route::post('/purchase-orders/{purchaseOrder}/payment', [PurchaseOrderController::class, 'recordPayment'])->name('purchase-orders.payment');

    // Inventory Management (Module 11)
    Route::resource('ingredients', IngredientController::class)->except(['create', 'show', 'edit']);
    Route::get('/inventory/movements', [InventoryAdjustmentController::class, 'index'])->name('inventory.movements.index');
    Route::post('/inventory/movements', [InventoryAdjustmentController::class, 'store'])->name('inventory.movements.store');
    Route::get('/inventory/transfers', [InventoryAdjustmentController::class, 'transfers'])->name('inventory.transfers.index');
    Route::post('/inventory/transfers', [InventoryAdjustmentController::class, 'storeTransfer'])->name('inventory.transfers.store');
    Route::resource('inventory/waste', WasteLogController::class)->only(['index', 'store', 'destroy'])->names('inventory.waste');
    Route::resource('inventory/stock-count', StockCountController::class)->only(['index', 'store', 'show'])->names('inventory.stock-count');

    // Recipe Management (Module 12)
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/builder/{menuItem}', [RecipeController::class, 'builder'])->name('recipes.builder');
    Route::post('/recipes/builder/{menuItem}', [RecipeController::class, 'saveBuilder'])->name('recipes.builder.save');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // Employees
    Route::get('/employees', [\App\Http\Controllers\Dashboard\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [\App\Http\Controllers\Dashboard\EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [\App\Http\Controllers\Dashboard\EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [\App\Http\Controllers\Dashboard\EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [\App\Http\Controllers\Dashboard\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [\App\Http\Controllers\Dashboard\EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [\App\Http\Controllers\Dashboard\EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Attendance
    Route::get('/attendance', [\App\Http\Controllers\Dashboard\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [\App\Http\Controllers\Dashboard\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [\App\Http\Controllers\Dashboard\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{date}/edit', [\App\Http\Controllers\Dashboard\AttendanceController::class, 'edit'])->name('attendance.edit');

    // Permissions
    Route::get('/permissions', [RoleController::class, 'permissionIndex'])->name('permission.index');
    Route::get('/permissions/create', [RoleController::class, 'permissionCreate'])->name('permission.create');
    Route::post('/permissions', [RoleController::class, 'permissionStore'])->name('permission.store');
    Route::get('/permissions/{id}/edit', [RoleController::class, 'permissionEdit'])->name('permission.edit');
    Route::put('/permissions/{id}', [RoleController::class, 'permissionUpdate'])->name('permission.update');
    Route::delete('/permissions/{id}', [RoleController::class, 'permissionDestroy'])->name('permission.destroy');

    // Roles
    Route::get('/roles', [RoleController::class, 'roleIndex'])->name('role.index');
    Route::get('/roles/create', [RoleController::class, 'roleCreate'])->name('role.create');
    Route::post('/roles', [RoleController::class, 'roleStore'])->name('role.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'roleEdit'])->name('role.edit');
    Route::put('/roles/{id}', [RoleController::class, 'roleUpdate'])->name('role.update');
    Route::delete('/roles/{id}', [RoleController::class, 'roleDestroy'])->name('role.destroy');

    // Role Permissions
    Route::get('/role-permissions', [RoleController::class, 'rolePermissionIndex'])->name('rolePermission.index');
    Route::get('/role-permissions/create', [RoleController::class, 'rolePermissionCreate'])->name('rolePermission.create');
    Route::post('/role-permissions', [RoleController::class, 'rolePermissionStore'])->name('rolePermission.store');
    Route::get('/role-permissions/{id}/edit', [RoleController::class, 'rolePermissionEdit'])->name('rolePermission.edit');
    Route::put('/role-permissions/{id}', [RoleController::class, 'rolePermissionUpdate'])->name('rolePermission.update');
    Route::delete('/role-permissions/{id}', [RoleController::class, 'rolePermissionDestroy'])->name('rolePermission.destroy');

    // Users
    Route::resource('users', UserController::class);

    // Expenses (Module 16)
    Route::resource('expenses', ExpenseController::class);
    Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');

    // Delivery Management (Module 15)
    Route::resource('delivery_zones', DeliveryZoneController::class);
    Route::resource('riders', RiderController::class);
    Route::resource('deliveries', DeliveryController::class);
    Route::post('/deliveries/{delivery}/assignRider', [DeliveryController::class, 'assignRider'])->name('deliveries.assignRider');
    Route::post('/deliveries/{delivery}/updateStatus', [DeliveryController::class, 'updateStatus'])->name('deliveries.updateStatus');

    Route::resource('coupons', CouponController::class);
    Route::resource('loyalty', LoyaltyCampaignController::class);
    Route::resource('notifications', NotificationCampaignController::class);

    // Multi-Branch & Franchise (Module 19)
    Route::resource('branches', BranchController::class);
    Route::resource('stock-transfers', StockTransferController::class)->only(['index', 'create', 'store', 'show']);
    Route::patch('/stock-transfers/{stockTransfer}/complete', [StockTransferController::class, 'complete'])->name('stock-transfers.complete');
        Route::patch('/stock-transfers/{stockTransfer}/cancel', [StockTransferController::class, 'cancel'])->name('stock-transfers.cancel');

        // Super Admin Panel (Module 21)
        Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
        Route::resource('support-tickets', SupportTicketController::class)->except(['edit', 'update', 'destroy']);
        Route::patch('/support-tickets/{supportTicket}/status', [SupportTicketController::class, 'updateStatus'])->name('support-tickets.update-status');

        // SaaS Tenant Management (Module 20)
    Route::resource('tenants', TenantController::class);

    // QR Ordering (Module 22) - Staff Side
    Route::get('/qr-menu/generate', [QRMenuController::class, 'generateQR'])->name('qr.generate');
    
    // Command Palette Search (Module 25)
    Route::get('/command-palette/search', [CommandPaletteController::class, 'search'])->name('command.search');
});

// QR Ordering (Module 22) - Customer Side (Outside Auth Middleware)
Route::get('/qr/menu', [QRMenuController::class, 'showMenu'])->name('qr.menu');
Route::post('/qr/order', [QRMenuController::class, 'placeOrder'])->name('qr.order');
Route::post('/qr/call-waiter', [QRMenuController::class, 'callWaiter'])->name('qr.callWaiter');
Route::get('/qr/track/{order_id}', [QRMenuController::class, 'trackOrder'])->name('qr.track');

require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\CashierController;

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

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::middleware('only_guest')->group(function(){
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticating']);
    Route::get('register', [AuthController::class, 'register']);
    Route::post('register', [AuthController::class, 'registerProcess']);
});

Route::middleware('auth')->group(function() {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('profile', [UserController::class, 'index'])->middleware('only_client');
    
    Route::post('/order/process', [OrderController::class, 'process'])->name('order.process');
    Route::post('/order-selected-items', [OrderController::class, 'orderSelectedItems'])->name('order.selectedItems');
    //Route to cashier
    Route::get('admincashier', [CashierController::class, 'index'])->middleware('only_admin');
    Route::post('addCashierAdmin/{slug}', [CashierController::class, 'storeCashier'])->middleware('only_admin');
    Route::get('newCashierAdmin', [CashierController::class, 'refreshCashier'])->middleware('only_admin');

    Route::get('/cashier', [ProductController::class, 'searchItem'])->name('cashier.searchItem')->middleware('only_client');
    Route::post('addCashier/{slug}', [OrderController::class, 'storeCashier'])->middleware('only_client');
    Route::get('newCashier', [OrderController::class, 'refreshCashier'])->middleware('only_client');
    
    Route::get('/purchaseproduct', [PurchaseController::class, 'purchases'])->name('purchaseproduct.index')->middleware('only_admin');
    Route::post('addPurchase/{slug}', [PurchaseController::class, 'storePurchase'])->middleware('only_admin');
    Route::get('newPurchase', [PurchaseController::class, 'refreshPurchase'])->middleware('only_admin');
    
    //only_admin can access this state
    Route::middleware('only_admin')->group(function(){
        Route::get('dashboard', [DashboardController::class, 'index']);

        //adminCategory
        Route::get('admincategory', [CategoryController::class, 'index']);
        Route::get('addcategory', [CategoryController::class,'add']);
        Route::post('addcategory', [CategoryController::class,'store']);
        Route::get('editcategory/{slug}', [CategoryController::class, 'edit']);
        Route::post('editcategory/{slug}', [CategoryController::class, 'update']);
        Route::get('deletecategory/{slug}', [CategoryController::class, 'delete']);
        Route::get('destroycategory/{slug}', [CategoryController::class, 'destroy']);
        Route::get('deletedcategory', [CategoryController::class, 'deletedCategory']);
        Route::get('restorecategory/{slug}', [CategoryController::class, 'restore']);
        Route::get('forcedeletecategory/{slug}', [CategoryController::class, 'forcedelete']);
        Route::get('forcedestroycategory/{slug}', [CategoryController::class, 'forcedestroy']);
        
        // Route::get('adminproduct', [ReportController::class,'index']);
        //adminProduct
        Route::get('adminproduct', [ProductController::class,'index']);
        Route::get('addproduct', [ProductController::class,'add']);
        Route::post('addproduct', [ProductController::class,'store']);
        Route::get('editproduct/{slug}', [ProductController::class,'edit']);
        Route::post('editproduct/{slug}', [ProductController::class, 'update']);
        Route::get('deleteproduct/{slug}', [ProductController::class, 'delete']);
        Route::get('destroyproduct/{slug}', [ProductController::class, 'destroy']);
        Route::get('deletedproduct', [ProductController::class, 'deletedShow']);
        Route::get('restoreproduct/{slug}', [ProductController::class, 'restore']);
        Route::get('forcedeleteproduct/{slug}', [ProductController::class, 'forceDelete']);
        Route::get('forcedestroyproduct/{slug}', [ProductController::class, 'forceDestroy']);

        //adminService
        Route::get('adminservice', [ServiceController::class, 'index']);
        Route::get('addservice', [ServiceController::class, 'addService']);
        Route::post('addservice', [ServiceController::class, 'store']);
        Route::get('editservice/{slug}',[ServiceController::class, 'edit']);
        Route::post('editservice/{slug}', [ServiceController::class, 'update']);
        Route::get('deleteservice/{slug}', [ServiceController::class, 'delete']);
        Route::get('destroyservice/{slug}', [ServiceController::class, 'destroy']);
        Route::get('deletedservice', [ServiceController::class, 'deletedShow']);
        Route::get('restoreservice/{slug}', [ServiceController::class, 'restore']);
        Route::get('forcedeleteservice/{slug}', [ServiceController::class, 'forceDelete']);
        Route::get('forcedestroyservice/{slug}', [ServiceController::class, 'forceDestroy']);
    });
    
});

Route::get('users', [UserController::class, 'user']);

Route::get('admintransaction', [TransactionController::class, 'index']);

Route::get('product', [ProductController::class, 'indexUser'])->name('product.index');
Route::get('detailproduct/{slug}', [ProductController::class, 'user']);


// Route::get('profile', [ProfileController::class, 'index']);
Route::get('editprofile/{slug}', [ProfileController::class, 'edit']);
Route::post('editprofile/{slug}', [ProfileController::class, 'update']);

Route::get('home', [PublicController::class, 'home']);
        
Route::get('aboutus', function()
{
    return view('aboutus');
});
        
//adminSlider
Route::get('adminslider', [SliderController::class, 'index']);
Route::get('addslider', [SliderController::class, 'add']);
Route::post('addslider', [SliderController::class, 'store']);
Route::get('editslider/{slug}', [SliderController::class, 'edit']);
Route::post('editslider/{slug}', [SliderController::class, 'update']);
Route::get('destroyslider/{slug}', [SliderController::class, 'destroy']);
Route::get('restoreslider/{slug}', [SliderController::class, 'restore']);
Route::get('forcedeleteslider/{slug}', [SliderController::class, 'forceDelete']);
Route::get('forcedestroyslider/{slug}', [SliderController::class, 'forceDestroy']);


Route::get('available/{slug}', [ServiceController::class, 'changeAvailable']);
Route::get('notavailable/{slug}', [ServiceController::class, 'changeNotAvailable']);

Route::get('usertransaction', [TransactionController::class, 'indexUser']);
        
//userService
Route::get('service', [ServiceController::class, 'indexUser']);
Route::get('/detailservice/{slug}', [ServiceController::class, 'detailService']);
    


//adminUser
Route::get('changeactive/{slug}', [UserController::class, 'changeActive']);
Route::get('changeinactive/{slug}', [UserController::class, 'changeInActive']);

Route::get('detailuser/{slug}', [UserController::class, 'detailUser']);
Route::get('destroyuser/{slug}', [UserController::class, 'destroy']);
Route::get('deleteduser', [UserController::class, 'deletedShow']);

//adminTransaction
Route::get('statusprocess/{order_id}', [TransactionController::class, 'changeStatusProcess']);
Route::get('statusnotprocess/{order_id}', [TransactionController::class, 'changeStatusNotProcess']);

Route::get('productcashier', [ProductController::class, 'indexCashier']);

Route::get('report', [TransactionController::class, 'reportData']);
Route::post('paidorder/{order_id}', [TransactionController::class, 'transactionSuccess']);

//export
Route::get('report/export/excel', [TransactionController::class, 'export']);
Route::get('report/export/pdf', [TransactionController::class, 'pdf']);


Route::post('adduser', [UserController::class, 'store']);

Route::get('reportdata', [ReportController::class, 'index']);


Route::get('modalproduct', [ProductController::class, 'modal']);
Route::get('modaldeleteduser', [UserController::class, 'deletedShow']);
Route::get('modaldeletedsliders', [SliderController::class, 'deletedShow']);
Route::get('modaleditcategory/{slug}', [CategoryController::class, 'edit']);
Route::post('modaleditservice/{slug}', [ServiceController::class, 'update']);
Route::get('modaldeletedcategory', [CategoryController::class, 'deletedShow']);
Route::get('modaldeletedservice', [ServiceController::class, 'deletedShow']);

Route::get('restoreuser/{slug}', [UserController::class, 'restore']);

Route::post('paidpurchase/{purchase_id}', [PurchaseController::class, 'purchaseSuccess']);
Route::get('getreport', [ReportController::class, 'storeReport']);

Route::get('adminexpenses', [ExpensesController::class, 'index']);
Route::get('addexpenses', [ExpensesController::class, 'add']);
Route::post('addexpenses', [ExpensesController::class, 'store']);
Route::post('editexpenses/{slug}', [ExpensesController::class, 'update']);
Route::get('destroyexpenses/{slug}', [ExpensesController::class, 'destroy']);
Route::get('restoreexpenses/{slug}', [ExpensesController::class, 'restore']);


Route::get('edituser',[UserController::class, 'edit']);
Route::post('edituser/{slug}', [UserController::class, 'update']);

Route::get('reportIncome/export/excel', [ReportController::class, 'export']);

Route::get('adminreportincome', [ReportController::class, 'index']);
Route::get('admintransactionpurchases', [PurchaseController::class, 'index']);

Route::get('exportPurchasePDF', [PurchaseController::class, 'exportPDF']);
Route::get('exportOrderPDF', [TransactionController::class, 'exportPDF']);

Route::get('forcedestroyuser/{slug}', [UserController::class, 'forceDestroy']);

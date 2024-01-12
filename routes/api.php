<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\orderController;

use Laravel\Passport\Passport;

Route::post('/info',function(request $request)
{
    return Auth::user();
});



//Routes for Auth.
Route::post('/warehouse/register',[App\Http\Controllers\authController::class,'register_warehouse']);//done
Route::post('/warehouse/login',[App\Http\Controllers\authController::class,'login_warehouse']);//done
Route::post('/pharmacy/register',[App\Http\Controllers\authController::class,'register_pharmacy']);//done
Route::post('/pharmacy/login',[App\Http\Controllers\authController::class,'login_pharmacy']);//done
Route::post('/warehouse/forget',[App\Http\Controllers\authController::class,'warehouse_forget']);//done
Route::post('/pharmacy/forget',[App\Http\Controllers\authController::class,'pharmacy_forget']);//done

Route::middleware('auth:api')->group( function () {
Route::get('/logout',[App\Http\Controllers\authController::class,'logout']);//done
Route::post('/reset_password',[App\Http\Controllers\authController::class,'reset_password']);//done
Route::post('/edit_information',[App\Http\Controllers\authController::class,'edit_info']);//done
Route::delete('/delete_user',[App\Http\Controllers\authController::class,'delete_the_user']);//done
});
//*********








//Routes for products.
Route::middleware('auth:api')->group( function () {
Route::get('/warehouse/all_products',[App\Http\Controllers\ProductController::class,'show_all_products_to_warehouse']);//done
Route::get('/warehouse/one_product/{name}',[App\Http\Controllers\ProductController::class,'search_to_product_for_warehouse']);//done
Route::post('/create_product',[App\Http\Controllers\ProductController::class,'create_products']);//done
Route::put('/edit_product/{id}',[App\Http\Controllers\ProductController::class,'edit_product']);//done
Route::delete('/delete_product/{id}',[App\Http\Controllers\ProductController::class,'delete_product']);//done
Route::post('add_or_delete_from_favorites',[App\Http\Controllers\ProductController::class,'add_or_delete_from_favorites']);//done
Route::get('/show_my_favorites',[App\Http\Controllers\ProductController::class,'show_my_favorites']);//done
});
Route::get('/all_products',[App\Http\Controllers\ProductController::class,'show_all_products']);//done
Route::get('/one_product/{id}',[App\Http\Controllers\ProductController::class,'show_one_product_to_warehouse']);//done
Route::get('/pharmacy/searching/{name}',[App\Http\Controllers\ProductController::class,'search_to_product_for_pharmacy']);//done
Route::get('/show_all_warehouses',[App\Http\Controllers\ProductController::class,'show_all_warehouses']);//done
Route::get('/show_products_in_warehouse/{id}',[App\Http\Controllers\ProductController::class,'show_products_in_warehouse']);//done

Route::get('/show_types',[App\Http\Controllers\ProductController::class,'show_all_types']);//done
Route::post('/show_all_products_in_one_type',[App\Http\Controllers\ProductController::class,'show_all_products_in_one_type']);//done

//****************



//Routes for orders.
Route::middleware('auth:api')->group( function () {
Route::get('/all_orders_warehouse',[App\Http\Controllers\orderController::class,'show_all_orders_to_warehouse']);//done
Route::get('/all_orders_pharmacy',[App\Http\Controllers\orderController::class,'show_all_orders_to_pharmacy']);//done
Route::get('/show_one_order_to_warehouse/{id}',[App\Http\Controllers\orderController::class,'show_one_order_to_warehouse']);//done
Route::get('/show_one_order_to_pharmacy/{id}',[App\Http\Controllers\orderController::class,'show_one_order_to_pharmacy']);//done
Route::post('/create_order',[App\Http\Controllers\orderController::class,'create_order']);//done
Route::post('/edit_orders_warehouse/{id}',[App\Http\Controllers\orderController::class,'edit_order_warehouse']);//done
Route::post('/edit_orders_pharmacy/{id}',[App\Http\Controllers\orderController::class,'edit_order_pharmacy']);
Route::delete('/delete_orders_warehouse/{id}',[App\Http\Controllers\orderController::class,'delete_order_to_warehouse']);//done
Route::delete('/delete_orders_pharmacy',[App\Http\Controllers\orderController::class,'delete_order_to_pharmacy']);
});
//************



//Routes for reports.
Route::middleware('auth:api')->group( function () {
Route::post('/show_all_reports_warehouse',[App\Http\Controllers\ReportController::class,'show_all_reports_warehouse']);//done
Route::post('/show_all_reports_pharmacy',[App\Http\Controllers\ReportController::class,'show_all_reports_pharmacy']);//done
});


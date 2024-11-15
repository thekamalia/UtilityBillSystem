<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilityBillingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('list-bill', [UtilityBillingController::class, 'listBill'])->name('listBill');
Route::get('form/{action}/{bill?}', [UtilityBillingController::class, 'form'])->name('form');
Route::post('submit-form/{action}/{bill?}', [UtilityBillingController::class, 'billform'])->name('billform');
Route::post('form/delete/{bill}', [UtilityBillingController::class, 'delete'])->name('delete');
Route::get('calculate-bill-report', [UtilityBillingController::class, 'reportCalculateBill'])->name('calculateBill');

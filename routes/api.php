<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->controller(ApiController::class)->group(function(){
    Route::post('/changePassword', 'changePassword')->name('api.changePassword');
    Route::post('/linkLaptop', 'linkLaptop')->name('api.linkLaptop');
    Route::post('/linkProject', 'linkProject')->name('api.linkProject');
    Route::get('/employees/search', 'getEmployeeByFilter')->name('api.filterEmployee');
    Route::get('/softwares/search', 'getSoftwareByFilter')->name('api.softwaresearch');
    Route::post('/softwarelinkProject', 'softwarelinkProject')->name('api.softwarelinkProject');
    Route::post('/deactivateEmployee', 'deactivateEmployee');
    Route::post('/notifySurrender', 'notifySurrenderOfLaptops');
    Route::post('/reactivateEmployee', 'reactivateEmployee');
    Route::get('/laptops/search', 'filterLaptopList')->name('api.filterLaptop');
    Route::post('/laptops/update', 'updateLaptopDetails')->name('api.updateLaptop');
    Route::post('/laptops/updateLinkage', 'updateLaptopLinkage')->name('api.updateLaptopLinkage');
    Route::post('/laptops/registLinkage', 'registLaptopLinkage')->name('api.registLaptopLinkage');
});

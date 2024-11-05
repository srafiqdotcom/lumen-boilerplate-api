<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DeletionCallbackController;
use App\Http\Controllers\API\RedeemCodeController;
use App\Http\Controllers\API\FamilyCodeController;
use App\Http\Controllers\API\ReportingController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\EndpointController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => '/endpoint','middleware' => ['version']], function()  {

    Route::post('/user/redeemcode/signup', [UserController::class,'userSignUpWithRedeemCode'])->name('userSignUpWithRedeemCode');
    Route::post('/user/payment/signup', [UserController::class,'userSignUpWithPayment'])->name('userSignUpWithPayment');
    Route::post('/user/exist', [UserController::class,'isUserExist'])->name('isUserExist');
    Route::post('/user/signin', [UserController::class,'signInPrimaryUser'])->name('signInPrimaryUser');

    Route::post('/user/cepreactivate', [UserController::class,'cepReactivate'])->name('cepReactivate');

    Route::post('/user/transfercep', [UserController::class,'cepTransfer'])->name('cepTransfer');

    Route::post('/user/payment/update', [UserController::class,'userSignUpWithUpdatePurchase'])->name('userSignUpWithUpdatePurchase');

    Route::group(['middleware' => ['staticauth']], function()  {
        Route::get('/viewlogs', [DeletionCallbackController::class,'viewLogs'])->name('viewLogs');
        Route::post('/user/delete', [UserController::class,'deleteUser'])->name('deleteUser');
        Route::get('/viewlogs', [DeletionCallbackController::class,'viewLogs'])->name('viewLogs');
    });

    Route::post('/user/facebook/delete', [DeletionCallbackController::class,'dataDeletionCallback'])->name('dataDeletionCallback');

    Route::group(['middleware' => ['packageauth']], function()  {
        Route::post('/package/details', [\App\Http\Controllers\API\PackageController::class,'getPackageDetails'])->name('getPackageDetails');
    });


    //Route::post('/user/signin', [UserController::class,'userSignIn'])->name('userSignIn');

    Route::post('/user/activedevices', [UserController::class,'getActiveConsumerEndpoints'])->name('getActiveConsumerEndpoints');

    Route::post('/send/notification', [NotificationController::class,'sendNotificationToMobiles'])->name('sendNotificationToMobiles');

    Route::post('/verify/redeemcode', [RedeemCodeController::class,'verifyRedeemCode'])->name('verifyRedeemCode');
    Route::post('/user/familysharingcode/signin', [UserController::class,'familySharingCodeSignIn'])->name('familySharingCodeSignIn');
    Route::post('/user/check/accountstatus', [UserController::class,'checkAccountStatus'])->name('checkAccountStatus');

    Route::group(['middleware' => ['uuid']], function()  {
        Route::post('/user/removeaccount', [UserController::class,'removeAccountData'])->name('removeAccountData');

        Route::post('/familycode/revoke', [FamilyCodeController::class,'revokeFamilyCode'])->name('revokeFamilyCode');

        Route::post('/familycode/assign', [FamilyCodeController::class,'codeReservation'])->name('codeReservation');
        Route::post('/familycode/update', [FamilyCodeController::class,'codeUpdate'])->name('codeUpdate');
        Route::post('/familycode/listing', [FamilyCodeController::class,'codeListing'])->name('codeListing');
        Route::post('/familycode/generate', [FamilyCodeController::class,'codeGeneration'])->name('codeGeneration');
        Route::post('/reporting/unblock', [ReportingController::class,'oDDSupportCase'])->name('oDDSupportCase');

        Route::post('/reactivate/mv3', [UserController::class,'reActivateEndpointMV3'])->name('reActivateEndpointMV3');

        Route::post('/invoke/pushmechanism', [NotificationController::class,'updatePushSettings'])->name('updatePushSettings');
        Route::post('/splittunnel/whitelistedapps', [EndpointController::class,'getWhitelistedApps'])->name('getWhitelistedApps');
    });
    Route::post('/company/info', [EndpointController::class,'getCompanyInfo'])->name('getCompanyInfo');
    Route::post('/activate/mv3', [UserController::class,'activateEndpointMV3'])->name('activateEndpointMV3');

});

Route::group(['prefix' => '/internal','middleware' => ['version']], function()  {

    Route::post('/redeemcode/generate', [RedeemCodeController::class,'generateRedeemCode'])->name('generateRedeemCode');
    Route::post('/redeemcode/list', [RedeemCodeController::class,'getRedeemCodeList'])->name('getRedeemCodeList');

    Route::post('/test/url', [\App\Http\Controllers\API\TestController::class,'testUrl'])->name('testUrl');
    Route::post('/test/host', [\App\Http\Controllers\API\TestController::class,'testHost'])->name('testHost');

    Route::post('/broadcast/notification', [NotificationController::class,'sendSmsOnlyPush'])->name('sendSmsOnlyPush');
    Route::post('/rebroadcast/notification', [NotificationController::class,'resendSmsOnlyPush'])->name('resendSmsOnlyPush');
});

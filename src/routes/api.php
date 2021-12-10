<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Buki\AutoRoute\AutoRouteFacade as AutoRoute;

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

Route::post('/submit-login', 'Auth\\Auth_Controller@SubmitLogin')->name('login_submit');


/*
 *
 * USING AUTO ROUTE LIBRARY : https://github.com/izniburak/laravel-auto-routes
 */

$merchant_modules = ['omzet'];
$Autoroute        = new AutoRoute();
foreach ($merchant_modules as $m) {
    $route      = 'merchant/' . $m;
    $controller = 'Module\\' . ucwords($m) . '_Controller';
    $Autoroute::auto($route, $controller);
}



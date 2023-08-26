<?php

namespace Config;

use App\Controllers\Error as Error;
use App\Controllers\Commercial as Commercial;
use App\Controllers\Admin as Admin;
use App\API\V1 as APIV1;
use App\Controllers\CDN as CDN;

// Define hostname
define('XHOSTNAME', str_replace(['http://', 'https://'], '', $_ENV['app.baseURL']));

// Define local port
define('LOCAL_PORT', $_ENV['LOCAL_PORT']);

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
// $routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->setAutoRoute(false);

// Error page - for all
$routes->set404Override('App\Controllers\Error::error404');

// Function Commercial
function fcCommercial($routes)
{

    // Home page
    $routes->get('/', [Commercial\HomePage::class, 'index']);

    // Contact page
    $routes->get('contact', [Commercial\Contact::class, 'index']);

    // Catalog page
    $routes->get('product', [Commercial\Catalog\Product\Category::class, 'index']);
    $routes->get('product/(:segment)', [Commercial\Catalog\Product\Category::class, 'index/$1']);
    $routes->get('product/catalog/(:segment)', [Commercial\Catalog\Product\Catalog::class, 'index/$1']);

    $routes->get('service', [Commercial\Catalog\Service::class, 'index']);
    $routes->get('service/(:segment)', [Commercial\Catalog\Service::class, 'index/$1']);

    // Blog page
    $routes->get('blog', [Commercial\BlogPage::class, 'index']);
    $routes->get('blog/(:segment)', [Commercial\BlogPage::class, 'index/$1']);

    // 404
    $routes->get('(:any)', [Commercial::class, 'error404']);
}

// Function admin
function fcAdmin($routes)
{

    ### Authentication
    // Login
    $routes->get('login', [Admin\Auth\Login::class, 'index']);

    // Reset password
    $routes->get('reset_password', [Admin\Auth\ResetPass::class, 'index']);

    // Logout
    $routes->get('logout', [Admin\Auth\Logout::class, 'index']);


    ### Control page

    #### Information
    // Contact
    $routes->get('contact/profile', [Admin\Control\Contact\Profile::class, 'index']);
    $routes->get('contact/social_media', [Admin\Control\Contact\SocialMedia::class, 'index']);
    $routes->get('contact/marketplace', [Admin\Control\Contact\Marketplace::class, 'index']);

    // Payment method
    $routes->get('payment_method', [Admin\Control\PaymentMethod\PaymentMethodList::class, 'index']);
    $routes->get('payment_method/edit/(:segment)', [Admin\Control\PaymentMethod\PaymentMethodMod::class, 'edit/$1']);
    $routes->get('payment_method/new', [Admin\Control\PaymentMethod\PaymentMethodMod::class, 'new']);

    // Dashboard page
    $routes->get('/', [Admin\Control\Dashboard::class, 'index']);
    $routes->get('dashboard', [Admin\Control\Dashboard::class, 'index']);

    // Setting page
    $routes->get('settings', [Admin\Control\Settings::class, 'index']);


    #### Catalog page
    $routes->group('catalog', function ($routes) {

        // Product
        $routes->group('product', function ($routes) {

            $routes->get('list', [Admin\Control\Catalog\Product\Lists::class, 'index']);
            $routes->get('list/edit/(:segment)', [Admin\Control\Catalog\Product\ListMod::class, 'edit/$1']);
            $routes->get('list/new', [Admin\Control\Catalog\Product\ListMod::class, 'new']);

            $routes->get('category', [Admin\Control\Catalog\Product\Category::class, 'index']);
            $routes->get('category/edit/(:segment)', [Admin\Control\Catalog\Product\CategoryMod::class, 'edit/$1']);
            $routes->get('category/new', [Admin\Control\Catalog\Product\CategoryMod::class, 'new']);
        });

        // Service
        $routes->group('service', function ($routes) {

            $routes->get('category', [Admin\Control\Catalog\Service\Category::class, 'index']);
            $routes->get('category/edit/(:segment)', [Admin\Control\Catalog\Service\CategoryMod::class, 'edit/$1']);
            $routes->get('category/new', [Admin\Control\Catalog\Service\CategoryMod::class, 'new']);
        });
    });

    // 404
    $routes->get('(:any)', 'AdminController::error404');
}

// Function API
function fcApi($routes)
{

    ### Authentication
    $routes->group('auth', ['filter' => 'auth/basic'], function ($routes) {

        // Login to account using email and password
        $routes->post('account', [APIV1\Auth\Account\Login::class, 'index']);

        // Request pass using registered email
        $routes->post('account/reset_pass', [APIV1\Auth\Account\ResetPass\Request::class, 'index']);

        // Validate reset password with new password
        $routes->post('account/reset_pass/reset', [APIV1\Auth\Account\ResetPass\Reset::class, 'index']);
    });


    ### Catalog
    $routes->group('catalog', ['filter' => 'auth/bearer'], function ($routes) {

        #### Catalog of Product
        $routes->group('product', function ($routes) {

            ##### Category
            // Get
            $routes->get('category', [APIV1\Catalog\Product\Category\Get::class, 'index']);
            $routes->get('category/(:segment)', [APIV1\Catalog\Product\Category\Get::class, 'index/$1']);

            // Insert
            $routes->post('category', [APIV1\Catalog\Product\Category\Insert::class, 'index']);

            // Update
            $routes->put('category', [APIV1\Catalog\Product\Category\Update::class, 'index']);
            $routes->put('category/(:segment)', [APIV1\Catalog\Product\Category\Update::class, 'index/$1']);

            // Delete
            $routes->delete('category', [APIV1\Catalog\Product\Category\Delete::class, 'index']);
            $routes->delete('category/(:segment)', [APIV1\Catalog\Product\Category\Delete::class, 'index/$1']);

            ##### Lists
            // Get
            $routes->get('/', [APIV1\Catalog\Product\Lists\Get::class, 'index']);
            $routes->get('(:segment)', [APIV1\Catalog\Product\Lists\Get::class, 'index/$1']);

            // Insert
            $routes->post('/', [APIV1\Catalog\Product\Lists\Insert::class, 'index']);

            // Update
            $routes->put('/', [APIV1\Catalog\Product\Lists\Update::class, 'index/$1']);
            $routes->put('(:segment)', [APIV1\Catalog\Product\Lists\Update::class, 'index/$1']);

            // Delete
            $routes->delete('/', [APIV1\Catalog\Product\Lists\Delete::class, 'index']);
            $routes->delete('(:segment)', [APIV1\Catalog\Product\Lists\Delete::class, 'index/$1']);
        });

        #### Catalog of Services
        $routes->group('service', function ($routes) {

            #### Category
            // Get
            $routes->get('category/(:segment)', [APIV1\Catalog\Service\Category\Get::class, 'index/$1']);

            // Insert
            $routes->post('category', [APIV1\Catalog\Service\Category\Insert::class, 'index']);

            // Update
            $routes->put('category/(:segment)', [APIV1\Catalog\Service\Category\Update::class, 'index/$1']);

            // Delete
            $routes->delete('category/(:segment)', [APIV1\Catalog\Service\Category\Delete::class, 'index/$1']);
        });
    });


    #### Contact
    $routes->put('contact/web_profile', [APIV1\Contact\WebProfile::class, 'index'], ['filter' => 'auth/bearer']);
    $routes->put('contact/social_media', [APIV1\Contact\SocialMedia::class, 'index'], ['filter' => 'auth/bearer']);
    $routes->put('contact/marketplace', [APIV1\Contact\Marketplace::class, 'index'], ['filter' => 'auth/bearer']);

    ### Payment method
    // Get
    $routes->get('payment_method', [APIV1\PaymentMethod\Get::class, 'index'], ['filter' => 'auth/bearer']);
    $routes->get('payment_method/(:segment)', [APIV1\PaymentMethod\Get::class, 'index/$1'], ['filter' => 'auth/bearer']);

    // Insert
    $routes->post('payment_method', [APIV1\PaymentMethod\Insert::class, 'index'], ['filter' => 'auth/bearer']);

    // Update
    $routes->put('payment_method', [APIV1\PaymentMethod\Update::class, 'index'], ['filter' => 'auth/bearer']);
    $routes->put('payment_method/(:segment)', [APIV1\PaymentMethod\Update::class, 'index/$1'], ['filter' => 'auth/bearer']);

    // Delete
    $routes->delete('payment_method', [APIV1\PaymentMethod\Delete::class, 'index'], ['filter' => 'auth/bearer']);
    $routes->delete('payment_method/(:segment)', [APIV1\PaymentMethod\Delete::class, 'index/$1'], ['filter' => 'auth/bearer']);


    ### Security
    $routes->group('security', ['filter' => 'auth/bearer'], function ($routes) {

        #### Password
        $routes->put('password', [APIV1\Security\Password\Update::class, 'index']);
    });

    // 404
    $routes->get('/(:any)', [Error::class, 'error404']);
}

// Function CDN
function fcCDN($routes)
{

    // Preview file
    $routes->get('(:any)', [CDN\Files::class, 'index']);
}



// Routes
// Admin
$routes->group(
    'admin',
    ['filter' => 'auth/cookieToken'],
    static function ($routes) {

        return fcAdmin($routes);
    }
);

// Console / API Routes
$routes->group(
    'myu_api',
    static function ($routes) {

        return fcApi($routes);
    }
);

// CDN / Resources Routes
$routes->group(
    'myu_cdn',
    static function ($routes) {

        return fcCDN($routes);
    }
);

// Commercial Routes
$routes->group(
    '',
    [
        'subdomain' => '/',
        'hostname' => XHOSTNAME,
        'namespace' => Commercial::class,
    ],
    static function ($routes) {

        return fcCommercial($routes);
    }
);

// www to -> non www
$routes->group(
    '',
    [
        'subdomain' => 'www',
        'hostname' => 'www.' . XHOSTNAME
    ],
    static function ($routes) {

        $routes->get('/', function () {

            return redirect()->to(base_url());
        });
    }
);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

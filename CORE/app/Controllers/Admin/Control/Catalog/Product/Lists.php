<?php

namespace App\Controllers\Admin\Control\Catalog\Product;

use App\Controllers\Admin\AdminController;
use App\Models\Catalog\Catalog;

class Lists extends AdminController
{

    private
        $dbCatalog;

    private $unauthorizedScheme;

    public function __construct()
    {

        $this->dbCatalog = new Catalog();

        /*** Command line below to use default unauthorized scheme in parent class - from this */
        // $this->unauthorizedScheme = function ($cbPar = []) {

        //     if (method_exists($this, '__unauthorizedScheme'))
        //     return $this->__unauthorizedScheme($cbPar);
        // };
        /*** Command line above to use default unauthorized scheme in parent class - to this */

        return Parent::__construct([
            'unauthorizedScheme' => $this->unauthorizedScheme
        ]);
    }

    // Function to override default unauthorized scheme in parent class
    private function __unauthorizedScheme($cbPar = [])
    {

        return $this->tryCatch(
            function ($cbPar) {

                // $err = new Error($this->request, $this->response);
                // return $err->error(500);
            },
            $cbPar
        );
    }

    // Server Response
    public function index()
    {

        $data = [];
        $data['catalog_lists'] = $this->catalogLists();

        return $this->viewPage('control/catalog/product/lists/index', $data);
    }

    // Function to get product category from db
    private function catalogLists($code = '')
    {

        $result = null;

        switch ($code) {

            case 1:
            default:

                $result = $this->dbCatalog->all([
                    'type' => 'PRODUCT',
                    'name' => $this->request->getGet('keyword')
                ]);
                break;
        }

        return $result;
    }
}

<?php

namespace App\Controllers\Admin\Control\Catalog\Product;

use App\Controllers\Admin\AdminController;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\CatalogCategory;

class ListMod extends AdminController
{

    private
        $dbCatalog,
        $dbCatCategory;

    private $unauthorizedScheme;

    public function __construct()
    {

        $this->dbCatalog = new Catalog();
        $this->dbCatCategory = new CatalogCategory();

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

    // New data page
    public function new($id = null)
    {

        $data['catalog_category'] = $this->catalogCategory(1);

        if ($data['catalog_category'] == null) return $this->viewPage('control/catalog/product/lists/error/index', $data);

        return $this->viewPage('control/catalog/product/lists/new/index', $data);
    }

    // Edit data page
    public function edit($id = null)
    {

        $data = [];
        $data['catalog_data'] = $this->catalogData(1, $id);
        $data['catalog_category'] = $this->catalogCategory(1);

        if ($data['catalog_category'] == null) return $this->viewPage('control/catalog/product/lists/error/index', $data);

        if ($data['catalog_data'] == null) return $this->error404();

        return $this->viewPage('control/catalog/product/lists/edit/index', $data);
    }

    // Function to get product category from db
    private function catalogData($code = '', $id = null)
    {

        $result = null;

        switch ($code) {

            case 1:
            default:

                $result = $this->dbCatalog->data([
                    'id' => $id,
                ]);
                break;
        }

        return $result;
    }

    // Function to get product category from db
    private function catalogCategory($code = '')
    {

        $result = null;

        switch ($code) {

            case 1:
            default:

                $result = $this->dbCatCategory->all([
                    'type' => 'PRODUCT'
                ]);

                if ($result != null) $result = $result['product']['list'];
                break;
        }

        return $result;
    }
}

<?php

namespace App\Controllers\Admin\Control\Catalog\Service;

use App\Controllers\Admin\AdminController;
use App\Models\Catalog\CatalogCategory;

class CategoryMod extends AdminController
{

    private
        $dbCatCategory;

    private $unauthorizedScheme;

    public function __construct()
    {

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

        return $this->viewPage('control/catalog/service/new/index');
    }

    // Edit data page
    public function edit($id = null)
    {

        $data = [];
        $data['catalog_category_data'] = $this->catalogCategoryData(1, $id);

        if ($data['catalog_category_data'] == null) return $this->error404();

        return $this->viewPage('control/catalog/service/edit/index', $data);
    }

    // Function to get product category from db
    private function catalogCategoryData(
        $code = '',
        $id = null
    ) {

        $result = null;

        switch ($code) {

            case 1:
            default:

                $result = $this->dbCatCategory->data([
                    'id' => $id,
                ]);
                break;
        }

        return $result;
    }
}

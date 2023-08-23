<?php

namespace App\Controllers\Admin\Control\Catalog\Service;

use App\Controllers\Admin\AdminController;
use App\Models\Catalog\CatalogCategory;

class Category extends AdminController
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

    // Server Response
    public function index()
    {

        $data = [];
        $data['catalog_category'] = $this->catalogCategory();

        return $this->viewPage('control/catalog/service/index', $data);
    }

    // Function to get product category from db
    private function catalogCategory($code = '')
    {

        $result = null;

        switch ($code) {

            case 1:
            default:

                $result = $this->dbCatCategory->all([
                    'type' => 'SERVICE',
                    'name' => $this->request->getGet('keyword')
                ]);

                if ($result != null) $result = $result['service']['list'];
                break;
        }

        return $result;
    }
}

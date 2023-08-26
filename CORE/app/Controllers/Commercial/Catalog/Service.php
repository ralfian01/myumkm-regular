<?php

namespace App\Controllers\Commercial\Catalog;

use App\Controllers\Commercial\CommercialController;
use App\Models\Catalog\CatalogCategory;
use App\Models\PaymentMethod;
use App\Models\AppManifest;

class Service extends CommercialController
{

    private $unauthorizedScheme;

    public function __construct()
    {

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
    public function index($id = null)
    {

        if ($id != null) return $this->catalogCategoryDetail($id);
        return $this->allCatalogCategory();
    }


    // Function to show all product category
    public function allCatalogCategory()
    {

        return $this->tryCatch(function ($cbPar) {

            return $this->viewPage('catalog/service/index');
        });
    }

    // Function to show all product category
    public function catalogCategoryDetail($id)
    {

        return $this->tryCatch(
            function ($cbPar) {

                $catCategoryData = $this->dbCatalogCategory(1, $cbPar['id']);

                $this
                    ->prepMeta([
                        'img_url' => cdnURL('image/' . $catCategoryData['image_path'])
                    ])
                    ->prepAddon([
                        'catalog_slug' => 'product/' . $cbPar['id'] . '/',
                        'catalog_category_data' => $catCategoryData,
                        'payment_method' => $this->paymentMethodList(1),
                        'payment_method_opt' => (new AppManifest())->paymentMethodOption(),
                    ]);

                return $this->viewPage('catalog/service/category_detail');
            },
            ['id' => $id]
        );
    }

    ### Function to process data from database
    // Function to get specific catalog category data
    private function dbCatalogCategory(
        $condition = 1,
        $identifier
    ) {

        $catCategory = new CatalogCategory();

        switch ($condition) {

            case 1:

                return $catCategory->data([
                    'slug' => 'service/' . $identifier
                ]);
                break;

            default:

                return null;
                break;
        }
    }

    // Function to get product category from db
    private function paymentMethodList(
        $code = ''
    ) {

        $dbPyMethodList = new PaymentMethod();

        $result = null;

        switch ($code) {

            case 1:
            default:

                $result = $dbPyMethodList->all();
                break;
        }

        return $result;
    }
}

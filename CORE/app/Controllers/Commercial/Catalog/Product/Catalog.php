<?php

namespace App\Controllers\Commercial\Catalog\Product;

use App\Controllers\Commercial\CommercialController;
use App\Models\Catalog\Catalog as CatalogData;

class Catalog extends CommercialController
{

    private $unauthorizedScheme;

    public function __construct()
    {

        /*** Command line below to use default unauthorized scheme in parent class - from this */
        // $this->unauthorizedScheme = function ($cbPar = []) {

        //     if (method_exists($this, '__unauthorizedScheme'))
        //         return $this->__unauthorizedScheme($cbPar);
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

        if ($id != null) return $this->catalogDetail($id);
        return $this->error404();
    }

    // Function to show all product category
    public function catalogDetail($id)
    {

        return $this->tryCatch(
            function ($cbPar) {

                $catalogData = $this->dbCatalogData(1, $cbPar['id']);
                $catalogList = $this->dbCatalogList(1, $catalogData['category_id']);

                $this
                    ->prepMeta([
                        'img_url' => cdnURL('image/' . $catalogData['image_path'][0]['path'])
                    ])
                    ->prepAddon([
                        'catalog_slug' => 'product/catalog/' . $cbPar['id'] . '/',
                        'catalog_data' => $catalogData,
                        'catalog_list' => $catalogList
                    ]);

                return $this->viewPage('catalog/product/catalog_detail');
            },
            ['id' => $id]
        );
    }

    // Function to get catalog list that related to slug
    private function dbCatalogList(
        $condition = 1,
        $identifier
    ) {

        $catList = new CatalogData();

        switch ($condition) {

            case 1:

                return $catList->all([
                    'category_id' => $identifier
                ]);
                break;

            case 2:

                return $catList->all([
                    'category_id' => $identifier
                ]);
                break;

            default:

                return null;
                break;
        }
    }

    // Function to get specific catalog data
    private function dbCatalogData(
        $condition = 1,
        $identifier
    ) {

        $catCategory = new CatalogData();

        switch ($condition) {

            case 1:

                return $catCategory->data([
                    'id' => $identifier
                ]);
                break;

            default:

                return null;
                break;
        }
    }
}

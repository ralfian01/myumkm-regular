<?php

namespace App\Controllers\Commercial\Catalog\Product;

use App\Controllers\Commercial\CommercialController;
use App\Models\Catalog\CatalogCategory;
use App\Models\Catalog\Catalog as catalogList;

class Category extends CommercialController
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
            function ($cbPar = null) {

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

        return $this->viewPage('catalog/product/index');
    }

    // Function to show all product category
    public function catalogCategoryDetail($id = null)
    {

        $catCategoryData = $this->dbCatalogCategory(1, $id);

        $this->prepMeta([
            'img_url' => cdnURL('image/' . $catCategoryData['image_path'])
        ]);

        $data = [];
        $data['catalog_slug'] = 'product/' . $id . '/';
        $data['catalog_category_data'] = $catCategoryData;
        $data['catalog_list'] = $this->dbCatalogList(1, $id);

        return $this->viewPage('catalog/product/category_detail', $data);
    }


    ### Function to process data from database
    // Function to get specific catalog category data
    private function dbCatalogCategory($condition = 1, $identifier = null)
    {

        $catCategory = new CatalogCategory();

        switch ($condition) {

            case 1:

                return $catCategory->data([
                    'slug' => 'product/' . $identifier
                ]);
                break;

            default:

                return null;
                break;
        }
    }

    // Function to get catalog list that related to slug
    private function dbCatalogList($condition = 1, $identifier = null)
    {

        $catList = new catalogList();

        switch ($condition) {

            case 1:

                return $catList->all([
                    'category_slug' => 'product/' . $identifier
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
}

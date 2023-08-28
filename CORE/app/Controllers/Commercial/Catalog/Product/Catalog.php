<?php

namespace App\Controllers\Commercial\Catalog\Product;

use App\Controllers\Commercial\CommercialController;
use App\Models\Catalog\Catalog as CatalogData;
use App\Models\PaymentMethod;
use App\Models\AppManifest;

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
    public function catalogDetail($id = null)
    {

        $catalogData = $this->dbCatalogData(1, $id);

        $this->prepMeta([
            'img_url' => cdnURL('image/' . $catalogData['image_path'][0]['path'])
        ]);

        $data = [];
        $data['catalog_data'] = $catalogData;
        $data['catalog_slug'] = 'product/catalog/' . $id . '/';
        $data['catalog_list'] = $this->dbCatalogList(1, $catalogData['category_id']);
        $data['payment_method'] = $this->paymentMethodList(1);
        $data['payment_method_opt'] = (new AppManifest())->paymentMethodOption();

        return $this->viewPage('catalog/product/catalog_detail', $data);
    }

    // Function to get catalog list that related to slug
    private function dbCatalogList($condition = 1, $identifier = null)
    {

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
    private function dbCatalogData($condition = 1, $identifier = null)
    {

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

    // Function to get product category from db
    private function paymentMethodList($code = '')
    {

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

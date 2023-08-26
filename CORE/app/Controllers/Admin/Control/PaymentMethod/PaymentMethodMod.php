<?php

namespace App\Controllers\Admin\Control\PaymentMethod;

use App\Controllers\Admin\AdminController;
use App\Models\AppManifest;
use App\Models\PaymentMethod;

class PaymentMethodMod extends AdminController
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
    public function new()
    {

        $data = [];
        $data['payment_method_option'] = (new AppManifest())->paymentMethodOption();

        return $this->viewPage('control/payment_method/new/index', $data);
    }

    // Server Response
    public function edit($id = null)
    {

        $data = [];
        $data['payment_method_option'] = (new AppManifest())->paymentMethodOption();
        $data['payment_method_data'] = $this->paymentMethodData(1, $id);

        return $this->viewPage('control/payment_method/edit/index', $data);
    }

    // Function to get product category from db
    private function paymentMethodData(
        $code = '',
        $id = null
    ) {

        $dbPyMethodList = new PaymentMethod();

        $result = null;

        switch ($code) {

            case 1:
            default:

                $result = $dbPyMethodList->data([
                    'id' => $id
                ]);
                break;
        }

        return $result;
    }
}

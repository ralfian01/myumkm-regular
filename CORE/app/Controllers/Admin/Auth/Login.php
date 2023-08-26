<?php

namespace App\Controllers\Admin\Auth;

use App\Controllers\Admin\AdminController;

class Login extends AdminController
{

    private $unauthorizedScheme;

    public function __construct()
    {

        /*** Command line below to use default unauthorized scheme in parent class - from this */
        $this->unauthorizedScheme = function ($cbPar = []) {

            if (method_exists($this, '__unauthorizedScheme'))
                return $this->__unauthorizedScheme($cbPar);
        };
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

                $this->prepare();
                return $this->view($cbPar['path']);
            },
            $cbPar
        );
    }

    // Index method
    public function index()
    {

        return $this->authHandler(
            function ($cbPar = []) {

                return redirect()->to($cbPar['redirect_url']);
            },
            $this->unauthorizedScheme,
            [
                'path' => 'auth/login',
                'redirect_url' => $_GET['continue'] ?? adminURL('')
            ]
        );
    }
}

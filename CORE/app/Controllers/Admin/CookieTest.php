<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminController;

class CookieTest extends AdminController
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

                // Try to get cookie
                var_dump($_COOKIE);

                // setcookie('hesoyam', '', -1);

                // setcookie('hesoyam', 'hesoyam', 0);

                // Try to make cookie controller
                // setcookie('hesoyam', 'hesoyam', [
                //     // 'expires' => time(),
                //     'domain' => 'localhost',
                //     'sameSite' => 'Lax',
                //     'secure' => false,
                //     'httpOnly' => false
                // ]);
            },
            $cbPar
        );
    }

    // Index method
    public function index()
    {

        return $this->authHandler(
            function ($cbPar = []) {

                return redirect()->to(adminURL(''));
            },
            $this->unauthorizedScheme,
            ['path' => 'auth/login']
        );
    }
}

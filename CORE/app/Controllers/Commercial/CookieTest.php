<?php

namespace App\Controllers\Commercial;

use App\Controllers\Commercial\CommercialController;

class CookieTest extends CommercialController
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
                // var_dump($_COOKIE);

                setcookie('hesoyam', '', 0, '/', '.projectkps.my.id', false, false);

                // setcookie('hesoyam', 'hiehdawd', [
                //     // 'domain' => 'localhost',
                //     'sameSite' => 'None',
                //     'secure' => false,
                //     'expires' => time() - 1000
                // ]);

                // Try to make cookie controller
                // setcookie('asulah', 'ihiy', [
                //     'domain' => 'localhost',
                //     // 'path' => '/',
                //     // 'samesite' => 'None',
                //     'secure' => false, // Set true if web support https request
                //     // 'httponly' => false
                // ]);
            },
            $cbPar
        );
    }

    // Index method
    public function index()
    {

        setcookie('hesoyam', '', -1);
        // setcookie('hesoyam', '', -1, '/', '.projectkps.my.id');

        setcookie('hesoyam', 'hesoyam', [
            // 'expires' => time(),
            'domain' => 'localhost',
            'sameSite' => 'Lax',
            'secure' => false,
            'httpOnly' => false
        ]);

        // return $this->authHandler(
        //     function ($cbPar = []) {

        //         return redirect()->to(adminURL(''));
        //     },
        //     $this->unauthorizedScheme,
        //     ['path' => 'auth/login']
        // );
    }
}

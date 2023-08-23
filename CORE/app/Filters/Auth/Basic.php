<?php

namespace App\Filters\Auth;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\TopSecret;
use App\Models\Account;
use stdClass;

class Basic implements FilterInterface
{

    private
        $secret,
        $dbAcc;

    public function __construct()
    {

        $this->secret = new TopSecret();
        $this->dbAcc = new Account();
    }

    public function before(RequestInterface $request, $arguments = null)
    {

        $user = $request->getServer('PHP_AUTH_USER');
        $pass = $request->getServer('PHP_AUTH_PW');
        $auth = [
            'status' => false,
            'data' => []
        ];

        if ($user != null && $pass != null) {

            // Basic Authentication with account username / email dan password
            $dbAcc = $this->dbAcc->data([
                'email' => $user,
                'username' => $user,
                'password' => hash('sha256', $pass)
            ]);

            if ($dbAcc != null) {

                $auth['status'] = true;
            } else if (
                $user == $this->secret->origin('basic_auth')['user']
                && $pass == $this->secret->origin('basic_auth')['pass']
            ) {

                // Basic Authentication with by pass username and password
                $auth['status'] = true;
            }

            if ($auth['status']) {

                $auth['data'] = [
                    'username' => $dbAcc['username']
                ];
            }
        }

        // Return authentication
        $request->auth = new stdClass();
        $request->auth->status = $auth['status'];
        $request->auth->data = $auth['data'];

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 
    }
}

<?php

namespace App\Filters\Auth;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Account;
use Config\TopSecret;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use stdClass;

class CookieToken implements FilterInterface
{

    private
        $secret,
        $dbAcc;

    public function __construct()
    {

        $this->dbAcc = new Account();
        $this->secret = new TopSecret();
    }

    public function before(RequestInterface $request, $arguments = null)
    {

        $auth = [
            'status' => false,
            'data' => []
        ];

        if (isset($_COOKIE['_PTS-Auth:Token'])) {

            // Start check JWT Token age
            try {

                $jwtObject = JWT::decode($_COOKIE['_PTS-Auth:Token'], new Key($this->secret->origin('bearer_key'), 'HS256'));

                $accUUID = base64_decode($jwtObject->uid_b64);
                $accData = $this->dbAcc->data([
                    'uuid' => $accUUID
                ]);

                if ($accData == null) throw new Exception();

                $auth['status'] = true;
                $auth['data'] = $accData;
            } catch (Exception $exc) {

                $auth['status'] = false;
                $auth['data'] = [];
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

        // Write code here
    }
}

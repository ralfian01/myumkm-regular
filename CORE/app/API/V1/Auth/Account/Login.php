<?php

namespace App\API\V1\Auth\Account;

use App\API\V1\APIV1Controller;
use Firebase\JWT\JWT;
use Config\TopSecret;
use App\Models\Account;

class Login extends APIV1Controller
{

    // Authentication
    private $auth = [];

    // Request payload
    private $payload = [];

    // Request file
    private $file = [];

    /* Edit this line to set payload rules - start */
    private $payloadRules = [];
    /* end */

    public function __construct(array $payload = [], array $file = [], array $auth = [])
    {

        // Do not edit line below
        Parent::__construct();

        $this->payload = $payload;
        $this->file = $file;
        $this->auth = $auth;
        return $this;
    }


    /** Index method that called from Routes.php
     * 
     * @return void
     */
    public function index($id = null)
    {

        $this->payload['id'] = $id;

        /* Do no edit line below */
        return Parent::setup(
            $this->payload,
            $this->payloadRules,
            function ($payload = [], $file = [], $auth = []) {

                $this->payload = $payload;
                $this->file = $file;
                $this->auth = $auth;
                return $this->core();
            }
        );
    }


    /** Function to handle core activity
     * 
     * @param array $payload
     * 
     * @return void
     */
    private function core($payload = [])
    {

        $response = $this->getJWT();
        return $this->respond($response);
    }


    /**
     * Function to get JWT Token
     * 
     * @param array $data
     * 
     * @return array
     */
    public function getJWT()
    {

        $secret = new TopSecret();
        $dbAcc = new Account();

        // Get account data
        $dbAcc = $dbAcc->data([
            'username' => $this->request->auth->data['username']
        ]);

        // Start create JWT Token
        $reqTime = time();
        $expTime = $reqTime + (3600 * 24); // 1 Hour * 24: Expires in 24 hours
        $jsonJWT = [
            'iss' => 'Putsutech JWT Authentication',
            'iat' => $reqTime,
            'exp' => $expTime,
            'host' => str_replace([$_ENV['LOCAL_PORT']], '', XHOSTNAME),
            'uid_b64' => base64_encode($dbAcc['uuid']),
            'email' => $dbAcc['email'],
            'ua' => base64_encode($_SERVER['HTTP_USER_AGENT']),
        ];

        $jwtToken = JWT::encode($jsonJWT, $secret->origin('bearer_key'), 'HS256');

        return [
            'token' => $jwtToken
        ];
    }
}

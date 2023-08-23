<?php

namespace App\API\V1\Security\Password;

use App\API\V1\APIV1Controller;
use App\Models\Account;

helper('function');

class Update extends APIV1Controller
{

    // Authentication
    private $auth = [];

    // Request payload
    private $payload = [];

    // Request file
    private $file = [];

    /* Edit this line to set payload rules - start */
    private $payloadRules = [
        'old_pass' => ['required'],
        'new_pass' => ['required'],
    ];
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

    /** Function to handle core process of API endpoint
     * 
     * @return void
     */
    private function core()
    {

        return $this->update();
    }

    /** Function to get JWT Token
     * 
     * @param array $data
     * 
     * @return object
     */
    public function update()
    {

        $dbAccount = new Account();

        // Check id
        $data = $dbAccount->data([
            'uuid' => $this->auth['uuid'],
            'password' => hash('sha256', $this->payload['old_pass'])
        ]);

        ### If id not found
        if ($data == null) {
            $this->setErrorStatus(409, ['description' => 'Wrong password']);
            return $this->error(409);
        }

        try {

            // If id found and Delete keys that have a null value
            $updatePayload = removeNullValues([
                'password' => isset($this->payload['new_pass']) ? hash('sha256', $this->payload['new_pass']) : null
            ]);

            $update = $dbAccount->update(
                ['id' => $this->auth['account_id']],
                $updatePayload
            );

            ### If update fail
            if (!$update) return $this->error(500);

            ### If update success
            return $this->respond([]);
        } catch (\Exception $e) {

            if ($e->getMessage() == 'There is no data to update.') {

                ### If no data updated
                return $this->respond(204, [
                    'reason' => $e->getMessage()
                ]);
            }

            if ($_ENV['CI_ENVIRONMENT'] != 'production') print_r($e);

            return $this->error(500, [
                'message' => $e->getMessage()
            ]);
        }
    }
}

<?php

namespace App\API\V1\PaymentMethod;

use App\API\V1\APIV1Controller;
use App\Models\PaymentMethod;

helper('function');

class Delete extends APIV1Controller
{

    // Authentication
    private $auth = [];

    // Request payload
    private $payload = [];

    // Request file
    private $file = [];

    /* Edit this line to set payload rules - start */
    private $payloadRules = [
        'id' => ['required', 'base64']
    ];
    /* end */


    public function __construct(array $payload = [], array $file = [])
    {

        // Do not edit line below
        Parent::__construct();

        $this->payload = $payload;
        $this->file = $file;
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

        return $this->delete();
    }

    /** Function to get JWT Token
     * 
     * @param array $data
     * 
     * @return object
     */
    public function delete()
    {

        $dbPyMethod = new PaymentMethod();

        // Check id
        $data = $dbPyMethod->data([
            'id' => base64_decode($this->payload['id'])
        ]);

        ### If id not found
        if ($data == null) {
            $this->setErrorStatus(404, ['description' => 'Id Not Found']);
            return $this->error(404);
        }


        try {

            $delete = $dbPyMethod->delete(
                ['id' => base64_decode($this->payload['id'])]
            );

            ### If delete fail
            if (!$delete) return $this->error(500);

            ### If delete success
            return $this->respond([]);
        } catch (\Exception $e) {

            if ($e->getMessage() == 'There is no data to delete.') {

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

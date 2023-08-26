<?php

namespace App\API\V1\Contact;

use App\API\V1\APIV1Controller;
use App\Models\AppManifest;

helper('function');

class WebProfile extends APIV1Controller
{

    // Authentication
    private $auth = [];

    // Request payload
    private $payload = [];

    // Request file
    private $file = [];

    /* Edit this line to set payload rules - start */
    private $payloadRules = [
        'site_name' => ['required'],
        'address' => ['required'],
        'email' => ['required'],
        'phone_number' => ['required']
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

        return $this->update();
    }

    /** Function to Update data
     * 
     * @param array $data
     * 
     * @return object
     */
    public function update()
    {

        try {

            $this->payload = removeNullValues($this->payload);

            if (!isset($this->payload) || empty($this->payload))
                throw new \Exception('There is no data to update.');

            $ownerContact = (new AppManifest())->ownerContact();

            $ownerContact['site_name'] = $this->payload['site_name'] ?? '';
            $ownerContact['address'] = $this->payload['address'] ?? '';
            $ownerContact['email'] = $this->payload['email'] ?? '';
            $ownerContact['phone_number'] = isset($this->payload['phone_number']) ? "62{$this->payload['phone_number']}" : '';
            $ownerContact['office_number'] = $this->payload['office_number'] ?? '';

            // If id found and Delete keys that have a null value
            $updatePayload = json_encode($ownerContact);

            $update = (new AppManifest())->update(
                ['manifest_code' => 'OWNER_CONTACT'],
                ['manifest_value' => $updatePayload]
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

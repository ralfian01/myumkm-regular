<?php

namespace App\API\V1\Contact;

use App\API\V1\APIV1Controller;
use App\Models\AppManifest;

helper('function');

class SocialMedia extends APIV1Controller
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

            $ownerContact = (new AppManifest())->ownerContact();

            foreach ($ownerContact['social_media'] as $key => $val) {

                $ownerContact['social_media'][$key]['username'] = $this->payload[$key] ?? '';
            }

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

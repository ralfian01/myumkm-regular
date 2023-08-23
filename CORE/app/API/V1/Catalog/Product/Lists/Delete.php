<?php

namespace App\API\V1\Catalog\Product\Lists;

use App\API\V1\APIV1Controller;
use App\Models\Catalog\Catalog;
use App\API\Library\Image;
use Exception;

helper('function');

class Delete extends APIV1Controller
{

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
            function ($payload = [], $file = []) {

                $this->payload = $payload;
                $this->file = $file;
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

        $dbCatalog = new Catalog();

        // Check id
        $data = $dbCatalog->data([
            'id' => base64_decode($this->payload['id'])
        ]);

        ### If id not found
        if ($data == null) {
            $this->setErrorStatus(404, ['description' => 'Id Not Found']);
            return $this->error(404);
        }

        try {

            // Delete image from upload folder
            $delImg = $this->deleteImage($data);
            if (!$delImg) return $this->error(500);

            $delete = $dbCatalog->delete(
                ['id' => base64_decode($this->payload['id'])]
            );

            ### If delete fail
            if (!$delete) return $this->error(500);

            ### If delete success
            return $this->respond([]);
        } catch (Exception $e) {

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


    // Function to delete image from storage server
    private function deleteImage(array $data = [])
    {

        $status = false;
        if (isset($data['image_path']) && count($data) >= 1) {
            foreach ($data['image_path'] as $val) {
                $status = Image::delete(Image::defaultBaseDirectory() . "/{$val['path']}");
            }
        }

        return $status;
    }
}

<?php

namespace App\API\V1\Catalog\Product\Lists;

use App\API\V1\APIV1Controller;
use App\Models\Catalog\CatalogCategory;
use App\Models\Catalog\Catalog;
use App\API\Library\Image;

class Insert extends APIV1Controller
{

    // Request payload
    private $payload = [];

    // Request file
    private $file = [];

    /* Edit this line to set payload rules - start */
    private $payloadRules = [
        'category_id' => ['required'],
        'name' => ['required', 'maxlength[100]'],
        'description' => ['maxlength[1000]'],
        'price' => ['required', 'int'],
        'status' => ['required', 'enum[ARCHIVE, SHOW, PENDING, DELETED]'],
        'thumbnails' => ['required', 'file', 'maxitem[3]', 'file_accept[png, jpeg, jpg]']
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

        return $this->insert();
    }

    /** Function to get JWT Token
     * 
     * @param array $data
     * 
     * @return object
     */
    public function insert()
    {

        // Check category id
        $dataCat = (new CatalogCategory())->data([
            'id' => $this->payload['category_id'],
            'type' => 'PRODUCT'
        ]);

        if ($dataCat == null) {
            $this->setErrorStatus(404, ['description' => 'Category Id Not Found']);
            return $this->error(404);
        }

        try {

            // Save image
            if (!is_array($this->file['thumbnails']))
                $this->file['thumbnails'] = [$this->file['thumbnails']];

            $this->payload['image_path'] = [];
            foreach ($this->file['thumbnails'] as $val) {

                $fdName = date('ymd');
                $image = new Image($val);
                $imgResult = $image
                    ->directoryTarget($fdName)
                    // ->setImageExtension('webp')
                    // ->setCompressSize(1, 0.5)
                    ->save();

                if ($imgResult->status)
                    $this->payload['image_path'][] = ['path' => "{$fdName}/{$imgResult->imageName}"];

                usleep(1500); // Sleep 150 milisecond
            }

            // If id found and Delete keys that have a null value
            $insertPayload = removeNullValues([
                'category_id' => $this->payload['category_id'],
                'name' => $this->payload['name'],
                'description' => $this->payload['description'] ?? null,
                'price' => $this->payload['price'],
                'status' => $this->payload['status'],
                'image_path' => json_encode($this->payload['image_path']),
            ]);

            $insert = (new Catalog())->insert($insertPayload);

            ### If update fail
            if (!$insert) return $this->error(500);

            ### If update success
            return $this->respond([]);
        } catch (\Exception $e) {

            if ($_ENV['CI_ENVIRONMENT'] != 'production') print_r($e);

            return $this->error(500, [
                'message' => $e->getMessage()
            ]);
        }
    }
}

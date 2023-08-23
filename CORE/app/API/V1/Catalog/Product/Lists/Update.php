<?php

namespace App\API\V1\Catalog\Product\Lists;

use App\API\V1\APIV1Controller;
use App\Models\Catalog\CatalogCategory;
use App\Models\Catalog\Catalog;
use App\API\Library\Image;

helper('function');

class Update extends APIV1Controller
{

    // Request payload
    private $payload = [];

    // Request file
    private $file = [];

    /* Edit this line to set payload rules - start */
    private $payloadRules = [
        'id' => ['required', 'base64'],
        // 'category_id' => [''],
        'name' => ['maxlength[100]'],
        'description' => ['maxlength[1000]'],
        'price' => ['int'],
        'status' => ['enum[ARCHIVE, SHOW, PENDING, DELETED]'],
        'thumbnails' => ['maxitem[3]', 'file', 'file_accept[png, jpeg, jpg]'],
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

            // Check category id
            if (isset($this->payload['catalog_id'])) {

                $checkCategoryId = $this->checkCategoryId($data);
                if (!$checkCategoryId->status)
                    return $this->showInteruption($checkCategoryId->err_detail);
            }

            // Check main image
            if (isset($this->payload['main_image'])) {

                $setMainImage = $this->setMainImage($data);
                if (!$setMainImage->status)
                    return $this->showInteruption($setMainImage->err_detail);
            }

            // Check deleted image
            if (isset($this->payload['delete_image'])) {

                $deleteImage = $this->deleteImage($data);
                if (!$deleteImage->status)
                    return $this->showInteruption($deleteImage->err_detail);
            }

            // Check new uploaded image
            if (isset($this->file['thumbnails'])) {

                $insertNewImage = $this->insertNewImage($data);
                if (!$insertNewImage->status)
                    return $this->showInteruption($insertNewImage->err_detail);
            }

            // If id found and Delete keys that have a null value
            $updatePayload = removeNullValues([
                'category_id' => $this->payload['category_id'] ?? null,
                'name' => $this->payload['name'] ?? null,
                'description' => $this->payload['description'] ?? null,
                'price' => $this->payload['price'] ?? null,
                'status' => $this->payload['status'] ?? null,
                'image_path' => isset($this->payload['image_path']) ? json_encode($this->payload['image_path']) : null,
            ]);

            $update = $dbCatalog->update(
                ['id' => base64_decode($this->payload['id'])],
                $updatePayload
            );

            ### If update fail
            if (!$update) return $this->error(500);

            ### If update success
            return $this->respond([]);
        } catch (\Exception $e) {

            if ($e->getMessage() == 'There is no data to update.') ### If no data updated
                return $this->respond(204, [
                    'reason' => $e->getMessage()
                ]);

            // Show Exception detail
            if ($_ENV['CI_ENVIRONMENT'] != 'production') print_r($e);

            return $this->error(500, [
                'message' => $e->getMessage()
            ]);
        }
    }


    // Function to show interuption error when any process does not complete
    private function showInteruption($err = [])
    {

        $this->setErrorStatus($err['code'], $err['set_status']);
        return $this->error($err['code'], $err['detail']);
    }

    // Function to check catalog category id
    private function checkCategoryId()
    {

        $return = new \stdClass();
        $return->status = true;

        $dataCat = (new CatalogCategory())->data([
            'id' => $this->payload['catalog_id'],
            'type' => 'PRODUCT'
        ]);

        if ($dataCat == null) {

            $return->status = false;
            $return->err_detail = [
                'code' => 404,
                'set_status' => [
                    'description' => 'Catalog Id Not Found',
                ],
                'detail' => []
            ];
            return $return;
        }
    }

    // Function to set main image
    private function setMainImage(array $data = [])
    {

        $return = new \stdClass();
        $return->status = true;

        // Show error
        if ($this->payload['main_image'] > count($data['image_path'])) {

            $return->status = false;
            $return->err_detail = [
                'code' => 409,
                'set_status' => [
                    'description' => 'Invalid number of image',
                ],
                'detail' => [[
                    'payload_name' => 'main_image',
                    'reasons' => [
                        'reason' => 'The specified image number is not available',
                        'expectation' => 'Can only choose images from numbers 1 - ' . count($data['image_path'])
                    ]
                ]]
            ];
            return $return;
        }


        // Set new main image
        $this->payload['image_path'] = [];
        foreach ($data['image_path'] as $key => $val) {

            if ($key == ($this->payload['main_image'] - 1)) {
                array_unshift($this->payload['image_path'], $val);
                continue;
            }

            $this->payload['image_path'][] = $val;
        }

        return $return;
    }

    // Function to delete image
    private function deleteImage(array $data = [])
    {

        $return = new \stdClass();
        $return->status = true;

        $imagePath = $data['image_path'];
        $deleteImage = explode(',', $this->payload['delete_image']);
        foreach ($deleteImage as $val) {

            $val = ($val - 1);

            // Show error
            if (($val > count($data['image_path']) || count($data['image_path']) <= 1)) {

                $return->status = false;
                $return->err_detail = [
                    'code' => 409,
                    'set_status' => [
                        'description' => 'Invalid number of image',
                    ],
                    'detail' => [[
                        'payload_name' => 'delete_image',
                        'reasons' => [
                            'reason' => 'The specified image number is not available',
                            'expectation' => count($data['image_path']) <= 1
                                ? 'Each product must have at least 1 image'
                                : 'Can only choose images from numbers 1 - ' . count($data['image_path'])
                        ]
                    ]]
                ];
                return $return;
            }

            // Delete image from storage server
            $delete = Image::delete(Image::defaultBaseDirectory() . "/{$imagePath[$val]['path']}");

            // Delete image from DB
            if ($delete) unset($imagePath[$val]);
        }

        // Rearrange array
        foreach ($imagePath as $val) {

            $this->payload['image_path'][] = $val;
        }

        return $return;
    }

    // Function to insert new uploaded image
    private function insertNewImage(array $data = [])
    {

        $return = new \stdClass();
        $return->status = true;

        if (!is_array($this->file['thumbnails']))
            $this->file['thumbnails'] = [$this->file['thumbnails']];

        // Show error
        if ((count($data['image_path']) + count($this->file['thumbnails'])) > 3) {

            $return->status = false;
            $return->err_detail = [
                'code' => 400,
                'set_status' => [
                    'description' => 'The maximum number of images that can be stored has been exceeded',
                ],
                'detail' => [[
                    'payload_name' => 'thumbnails',
                    'reasons' => [
                        'reason' => 'Can only store a maximum of 3 images per product',
                        'expectation' => 'Keep images of no more than 3 images per catalog'
                    ]
                ]]
            ];
            return $return;
        }

        // Save image
        if (empty($this->payload['image_path']) || isset($this->payload['image_path']))
            $this->payload['image_path'] = $data['image_path'];

        foreach ($this->file['thumbnails'] as $val) {

            $fdName = date('ymd');
            $image = new Image($val);
            $imgResult = $image
                ->directoryTarget($fdName)
                ->setImageExtension('webp')
                ->setCompressSize(1, 0.5)
                ->save();

            if ($imgResult->status)
                $this->payload['image_path'][] = ['path' => "{$fdName}/{$imgResult->imageName}"];

            usleep(1500); // Sleep 150 milisecond
        }

        return $return;
    }
}

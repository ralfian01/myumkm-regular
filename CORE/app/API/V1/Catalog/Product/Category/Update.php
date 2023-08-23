<?php

namespace App\API\V1\Catalog\Product\Category;

use App\API\V1\APIV1Controller;
use App\Models\Catalog\CatalogCategory;
use App\API\Library\Image;
use Exception;

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
        'slug' => ['segment_domain'],
        'name' => ['maxlength[100]'],
        'description' => ['maxlength[500]'],
        'thumbnail' => ['file', 'file_accept[png, jpeg, jpg]', 'single_file']
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

        $dbCatCategory = new CatalogCategory();

        // Check id
        $data = $dbCatCategory->data([
            'id' => base64_decode($this->payload['id']),
            'type' => 'PRODUCT'
        ]);

        ### If id not found
        if ($data == null) {
            $this->setErrorStatus(404, ['description' => 'Id Not Found']);
            return $this->error(404);
        }


        // Check slug usage
        $dataSlug = null;
        if (isset($this->payload['slug']))
            $dataSlug = $dbCatCategory->data(['slug' => "product/{$this->payload['slug']}"]);

        if ($dataSlug != null && $dataSlug['category_id'] != base64_decode($this->payload['id'])) {
            $this->setErrorStatus(409, ['description' => 'Link already used']);
            return $this->error(409);
        }

        try {

            // Save image
            if (isset($this->file['thumbnail'])) {

                $fdName = date('ymd');
                $image = new Image($this->file['thumbnail']);
                $imgResult = $image
                    ->directoryTarget($fdName)
                    ->setImageExtension('webp')
                    ->setCompressSize(1, 0.5)
                    ->save();

                if ($imgResult->status) {

                    // Delete previoud image
                    Image::delete("{$image->getBaseDirectory()}/{$data['image_path']}");
                    // New image path
                    $this->payload['image_path'] = "{$fdName}/{$imgResult->imageName}";
                }
            }

            // If id found and Delete keys that have a null value
            $updatePayload = removeNullValues([
                'category_name' => $this->payload['name'] ?? null,
                'slug' => isset($this->payload['slug']) ? ('product/' . $this->payload['slug']) : null,
                'description' => $this->payload['description'] ?? null,
                'image_path' => $this->payload['image_path'] ?? null,
            ]);

            $update = $dbCatCategory->update(
                ['id' => base64_decode($this->payload['id'])],
                $updatePayload
            );

            ### If update fail
            if (!$update) return $this->error(500);

            ### If update success
            return $this->respond([]);
        } catch (Exception $e) {

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

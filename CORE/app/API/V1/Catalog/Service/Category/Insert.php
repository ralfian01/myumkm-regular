<?php

namespace App\API\V1\Catalog\Service\Category;

use App\API\V1\APIV1Controller;
use App\Models\Catalog\CatalogCategory;
use App\API\Library\Image;

class Insert extends APIV1Controller
{

    // Request payload
    private $payload = [];

    // Request file
    private $file = [];

    /* Edit this line to set payload rules - start */
    private $payloadRules = [
        'name' => ['required', 'maxlength[100]'],
        'slug' => ['required', 'segment_domain'],
        'description' => ['maxlength[500]'],
        'thumbnail' => ['required', 'file', 'file_accept[png, jpeg, jpg]', 'single_file']
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

        $dbCatCategory = new CatalogCategory();

        // Check slug usage
        $dataSlug = null;
        if (isset($this->payload['slug']))
            $dataSlug = $dbCatCategory->data(['slug' => "service/{$this->payload['slug']}",]);

        if ($dataSlug != null) {
            $this->setErrorStatus(409, ['description' => 'Link already used']);
            return $this->error(409);
        }

        try {

            // Save image
            $fdName = date('ymd');
            $image = new Image($this->file['thumbnail']);
            $imgResult = $image
                ->directoryTarget($fdName)
                ->setImageExtension('webp')
                ->setCompressSize(1, 0.5)
                ->save();

            if ($imgResult->status)
                $this->payload['image_path'] = "{$fdName}/{$imgResult->imageName}";

            // If id found and Delete keys that have a null value
            $insertPayload = removeNullValues([
                'category_name' => $this->payload['name'],
                'slug' => ('service/' . $this->payload['slug']),
                'description' => $this->payload['description'] ?? null,
                'image_path' => $this->payload['image_path'],
                'type' => 'SERVICE'
            ]);

            $insert = $dbCatCategory->insert($insertPayload);

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

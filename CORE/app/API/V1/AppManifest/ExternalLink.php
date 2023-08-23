<?php

namespace App\API\V1\AppManifest;

use App\Controllers\BaseController;

class ExternalLink extends BaseController
{

    public function __construct()
    {

        $this->response = service('response');
        $this->model = new \App\Models\AppManifest();
    }

    public function get()
    {

        $responseBody['code'] = 200;
        $responseBody['body'] = [
            'code' => $responseBody['code'],
            'data' => $this->model->apiTextTemplate()
        ];

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($responseBody['code'])
            ->setBody(json_encode($responseBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function patch()
    {

        $category = [
            'instagram', 'line', 'whatsapp', 'facebook', 'youtube', 'linkedin', 'twitter'
        ];

        // Get contact data as json
        $json = $this->model->contact();

        foreach ($_GET as $key => $value) {

            if (in_array($key, $category)) {

                $json[$key]['url'] = $value;
            }
        }

        $update = $this->model
            ->update(
                'OWNER_CONTACT',
                [
                    'manifest_value' => json_encode($json)
                ]
            );

        if ($update) {

            $resBody['code'] = 200;
            $resBody['body'] = [
                'code' => $resBody['code'],
                'status' => 'UPDATE_DATA_SUCCESS'
            ];
        } else {

            $resBody['code'] = 500;
            $resBody['body'] = [
                'code' => $resBody['code'],
                'status' => 'INTERNAL_ERROR',
                'description' => 'There is internal error when updating data'
            ];
        }

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function index(
        $_1 = false
    ) {

        $response = null;

        switch ($_SERVER['REQUEST_METHOD']) {

            case 'GET':

                $response = $this->get();
                break;

            case 'PATCH':

                $response = $this->patch($_1);
                break;
        }

        return $response;
    }
}

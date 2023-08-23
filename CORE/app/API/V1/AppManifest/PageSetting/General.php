<?php

namespace App\API\V1\AppManifest\PageSetting;

use App\Controllers\BaseController;

class General extends BaseController
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
            'data' => $this->model->pageSetting()
        ];

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($responseBody['code'])
            ->setBody(json_encode($responseBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function patch()
    {

        return 'worked';
    }

    public function index()
    {

        $response = null;

        switch ($_SERVER['REQUEST_METHOD']) {

            case 'GET':

                $response = $this->get();
                break;

            case 'PATCH':

                $response = $this->patch();
                break;
        }

        return $response;
    }
}

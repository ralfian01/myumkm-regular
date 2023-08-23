<?php

namespace App\API\V1\AppManifest;

use App\Controllers\BaseController;
use PhpParser\Node\Stmt\Break_;

class UploadSetting extends BaseController
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

    public function patch($settingId = false)
    {

        // Category of template id
        $category = [
            'image'
        ];

        if (
            !$settingId
            || !in_array($settingId, $category)
        ) {

            $resBody['code'] = 404;
            $resBody['body'] = [
                'code' => $resBody['code'],
                'status' => 'INVALID_SETTING_CODE',
                'description' => 'Setting code is invalid',
            ];
        } else {

            if (
                !isset($_GET['limit'])
                || !isset($_GET['compress'])
            ) {

                $resBody['code'] = 400;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'status' => 'INVALID_API_PARAMETER',
                    'description' => 'There are parameters that must be filled',
                    'detail' => [
                        'text' => 'required',
                    ]
                ];
            } else {

                // Get contact data as json
                $json = $this->model->uploadSetting();

                switch ($settingId) {

                    case 'image':

                        $json[$settingId]['compress'] = $_GET['compress'];
                        $json[$settingId]['max_size']['size'] = $_GET['limit'];
                        break;
                }

                $update = $this->model
                    ->update(
                        'UPLOAD_SETTING',
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
            }
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

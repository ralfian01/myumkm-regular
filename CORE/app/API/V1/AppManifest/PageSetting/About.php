<?php

namespace App\API\V1\AppManifest\PageSetting;

use App\Controllers\BaseController;

class About extends BaseController
{

    public function __construct()
    {

        $this->response = service('response');
        $this->model = new \App\Models\AppManifest();
        $this->image = \Config\Services::image();
    }

    public function allSetting()
    {

        $responseBody['code'] = 200;
        $responseBody['body'] = [
            'code' => $responseBody['code'],
            'data' => $this->model->aboutSetting()
        ];

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($responseBody['code'])
            ->setBody(json_encode($responseBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function greeting()
    {

        $resBody = [
            'code' => 500,
            'body' => [
                'code' => 500,
                'status' => 'INTERNAL_ERROR'
            ]
        ];

        switch ($_SERVER['REQUEST_METHOD']) {

            case 'PATCH':

                if (
                    isset($_GET['text'])
                    && is_string_base64($_GET['text'])
                ) {

                    $json = $this->model->aboutSetting();

                    $json['greeting'] = base64_decode($_GET['text']);

                    $upStatus = $this->model->update(
                        'PAGE_SETTING::ABOUT',
                        [
                            'manifest_value' => json_encode($json)
                        ]
                    );

                    if ($upStatus) {

                        $resBody['code'] = 200;
                        $resBody['body'] = [
                            'code' => $resBody['code'],
                            'status' => 'UPDATE_DATA_SUCCESS'
                        ];
                    } else {

                        $resBody['body']['description'] = 'There is internal error when updating data';
                    }
                } else {

                    $resBody['code'] = 400;
                    $resBody['body'] = [
                        'code' => $resBody['code'],
                        'status' => 'INVALID_API_PARAMETER',
                        'description' => 'There are parameters that must be filled'
                    ];
                }
                break;

            case 'GET':

                $resBody['code'] = 200;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'data' => $this->model->aboutSetting()['greeting']
                ];
                break;
        }

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function specialBox1()
    {

        $resBody = [
            'code' => 500,
            'body' => [
                'code' => 500,
                'status' => 'INTERNAL_ERROR'
            ]
        ];

        switch ($_SERVER['REQUEST_METHOD']) {

            case 'POST':

                ini_set('memory_limit', '4096M');

                $upStatus = false;
                $saveImage = $this->saveImage($this->request->getFile('image'));

                if ($saveImage->status) { // Store the image

                    $json = $this->model->aboutSetting();

                    $prevImage = $json['special_box']['box1']['image'];
                    $json['special_box']['box1']['image'] = $saveImage->path;

                    // Delete previous image
                    if (file_exists(WRITEPATH . 'uploads/' . $prevImage)) {

                        if (unlink(WRITEPATH . 'uploads/' . $prevImage)) {

                            $upStatus = $this->model->update(
                                'PAGE_SETTING::ABOUT',
                                [
                                    'manifest_value' => json_encode($json)
                                ]
                            );
                        }
                    } else {

                        $upStatus = $this->model->update(
                            'PAGE_SETTING::ABOUT',
                            [
                                'manifest_value' => json_encode($json)
                            ]
                        );
                    }
                }

                if ($upStatus) {

                    $resBody['code'] = 200;
                    $resBody['body'] = [
                        'code' => $resBody['code'],
                        'status' => 'PHOTO_UPLOADED'
                    ];
                } else {

                    $resBody['body']['description'] = 'There is internal error when updating data';
                }
                break;

            case 'GET':

                $resBody['code'] = 200;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'data' => $this->model->aboutSetting()['special_box']['box1']
                ];
                break;
        }

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }
}

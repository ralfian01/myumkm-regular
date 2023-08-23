<?php

namespace App\API\V1\AppManifest\PageSetting;

use App\Controllers\BaseController;

class Home extends BaseController
{

    public function __construct()
    {

        $this->response = service('response');
        $this->model = new \App\Models\AppManifest();
    }

    public function allSetting()
    {

        // Get all home setting data
        $resBody['code'] = 200;
        $resBody['body'] = [
            'code' => $resBody['code'],
            'data' => $this->model->homeSetting()
        ];

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function carousel()
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

                // $resBody['code'] = 200;
                // $resBody['body'] = [
                //     'code' => $resBody['code'],
                //     'data' => $this->model->homeSetting()
                // ];
                break;

            case 'PATCH':

                break;

            case 'GET':

                $resBody['code'] = 200;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'data' => $this->model->homeSetting()['carousel']
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

                    $json = $this->model->homeSetting();

                    $prevImage = '';

                    if ($_POST['code'] == 'image1') {

                        $prevImage = $json['special_box']['box1']['item1']['image'];

                        $json['special_box']['box1']['item1']['image'] = $saveImage->path;
                    } else if ($_POST['code'] == 'image2') {

                        $prevImage = $json['special_box']['box1']['item2']['image'];

                        $json['special_box']['box1']['item2']['image'] = $saveImage->path;
                    }

                    // Delete previous image
                    if (file_exists(WRITEPATH . 'uploads/' . $prevImage)) {

                        if (unlink(WRITEPATH . 'uploads/' . $prevImage)) {

                            $upStatus = $this->model->update(
                                'PAGE_SETTING::HOME',
                                [
                                    'manifest_value' => json_encode($json)
                                ]
                            );
                        }
                    } else {

                        $upStatus = $this->model->update(
                            'PAGE_SETTING::HOME',
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

            case 'PATCH':

                if (isset(
                    $_GET['title1'],
                    $_GET['title2'],
                    $_GET['longtext1'],
                    $_GET['longtext2'],
                )) {

                    $json = $this->model->homeSetting();

                    $setJson = $json['special_box']['box1'];
                    $setJson['item1']['title'] = $_GET['title1'];
                    $setJson['item1']['description'] = $_GET['longtext1'];
                    $setJson['item2']['title'] = $_GET['title2'];
                    $setJson['item2']['description'] = $_GET['longtext2'];

                    $json['special_box']['box1'] = $setJson;

                    $upStatus = $this->model->update(
                        'PAGE_SETTING::HOME',
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

                // Get data
                $resBody['code'] = 200;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'data' => $this->model->homeSetting()['special_box']['box1']
                ];
                break;
        }

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function specialBox2()
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

                    $json = $this->model->homeSetting();

                    $prevImage = $json['special_box']['box2'][$_POST['code']];
                    $json['special_box']['box2'][$_POST['code']] = $saveImage->path;

                    // Delete previous image
                    if (file_exists(WRITEPATH . 'uploads/' . $prevImage)) {

                        if (unlink(WRITEPATH . 'uploads/' . $prevImage)) {

                            $upStatus = $this->model->update(
                                'PAGE_SETTING::HOME',
                                [
                                    'manifest_value' => json_encode($json)
                                ]
                            );
                        }
                    } else {

                        $upStatus = $this->model->update(
                            'PAGE_SETTING::HOME',
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

            case 'PATCH':

                if (isset(
                    $_GET['title'],
                    $_GET['longtext'],
                )) {

                    $json = $this->model->homeSetting();

                    $setJson = $json['special_box']['box2'];
                    $setJson['title'] = $_GET['title'];
                    $setJson['description'] = $_GET['longtext'];

                    $json['special_box']['box2'] = $setJson;

                    $upStatus = $this->model->update(
                        'PAGE_SETTING::HOME',
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

                // Get data
                $resBody['code'] = 200;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'data' => $this->model->homeSetting()['special_box']['box1']
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

<?php

namespace App\API\V1\AppManifest;

use App\Controllers\BaseController;

class Contact extends BaseController
{

    public function __construct()
    {

        $this->response = service('response');
        $this->model = new \App\Models\AppManifest();
    }

    public function get()
    {

        $resBody['code'] = 200;
        $resBody['body'] = [
            'code' => $resBody['code'],
            'data' => $this->model->contact()
        ];

        $this->response
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->response;
    }

    public function patch($ctId = false)
    {

        // Category of contact id
        $category = [
            'general' => [
                'address', 'email', 'phone_number', 'office_number'
            ],
            'socmed' => [
                'instagram', 'twitter', 'line', 'linkedin', 'whatsapp', 'facebook', 'youtube', 'google_maps'
            ]
        ];

        if (
            !$ctId
            || (!in_array($ctId, $category['general']) && !in_array($ctId, $category['socmed']))
        ) {

            $resBody['code'] = 404;
            $resBody['body'] = [
                'code' => $resBody['code'],
                'status' => 'INVALID_CONTACT_CODE',
                'description' => 'Contact code is invalid',
            ];
        } else {

            if (
                !isset($_GET['name'])
                && !isset($_GET['url'])
            ) {

                $resBody['code'] = 400;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'status' => 'INVALID_API_PARAMETER',
                    'description' => 'There are parameters that must be filled',
                    'detail' => [
                        'name' => 'required when {url} parameter if empty',
                        'url' => 'required when {name} parameter if empty',
                    ]
                ];
            } else {

                // Get contact data as json
                $jsonContact = $this->model->contact();

                if (in_array($ctId, $category['general'])) {

                    $jsonContact[$ctId] = $_GET['name'];
                } else if (in_array($ctId, $category['socmed'])) {

                    if (isset($_GET['name'])) {

                        $jsonContact[$ctId]['name'] = $_GET['name'];

                        // Update socmed url
                        $url = parse_url($jsonContact[$ctId]['url']);
                        $url['path'] = '/' . $_GET['name'];

                        $jsonContact[$ctId]['url'] = build_url($url);
                    } else {

                        $jsonContact[$ctId]['url'] = $_GET['url'];
                    }
                }

                $update = $this->model
                    ->update(
                        'OWNER_CONTACT',
                        [
                            'manifest_value' => json_encode($jsonContact)
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

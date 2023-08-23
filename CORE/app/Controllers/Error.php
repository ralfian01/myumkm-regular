<?php

namespace App\Controllers;

use App\Controllers\ControllerPreProcessor;
use CodeIgniter\Config\Services;

class Error extends ControllerPreProcessor
{

    protected
        $resp,
        $ctrPrep,
        $responsePush,
        $requestPush;
    protected $base_path = 'overrideErrors/';

    public function __construct($response = [], $request = [])
    {

        $this->resp = Services::response();

        if (isset($response)) $this->responsePush = $response;
        if (isset($request)) $this->requestPush = $request;

        return $this;
    }

    // Function to prepare controller
    private function prepare($meta = [])
    {

        $this
            ->prepBasePath($this->base_path)
            ->prepResponse($this->response)
            ->prepMeta($meta);

        return $this;
    }

    // Function to print response in JSON format
    private function printJSON(
        $http_code = 200,
        $json = []
    ) {

        return $this->resp
            ->setContentType('application/json')
            ->setStatusCode($http_code) // Prevent cors error
            ->setBody(json_encode($json, JSON_PRETTY_PRINT));
    }

    // Function to show page based on content-type
    private function pushView($htmlView, $textView)
    {

        // Check request header
        $contentType = $this->request->header('content-type');

        if ($contentType == null) $contentType = $this->request->header('accept');

        if (strpos($contentType, 'text/html')) {

            return $htmlView();
        } else {

            return $textView();
        }
    }

    private function errorData($err_code)
    {

        $json = [
            'meta' => [],
            'text_format' => [],
            'html_path' => ''
        ];

        $err_code = intval($err_code);

        if ($err_code >= 400 && $err_code <= 499) {

            switch ($err_code) {

                case 400:
                default:

                    $json['meta']['title'] = 'Bad Request';
                    $json['meta']['description'] = 'Server cannot process the request due to something that is perceived to be a client error';

                    $json['html_path'] = '404';

                    $json['text_format'] = [
                        'code' => $err_code,
                        'status' => 'BAD_REQUEST',
                        'description' => 'Bad request'
                    ];
                    break;

                case 404:

                    $json['meta']['title'] = 'Page not found';
                    $json['meta']['description'] = 'The page you are looking for may have been removed or changed by the website owner';

                    $json['html_path'] = '404';

                    $json['text_format'] = [
                        'code' => $err_code,
                        'status' => 'NOT_FOUND',
                        'description' => 'URL not available'
                    ];
                    break;
            }
        } else if ($err_code >= 500 && $err_code <= 599) {

            switch ($err_code) {

                case 500:
                default:

                    $json['meta']['title'] = 'Bad Request';
                    $json['meta']['description'] = 'Server cannot process the request due to something that is perceived to be a client error';

                    $json['html_path'] = '500';

                    $json['text_format'] = [
                        'code' => $err_code,
                        'status' => 'INTERNAL_ERROR',
                        'description' => 'There is an error in internal server'
                    ];
                    break;
            }
        }

        return $json;
    }

    // Function to print error page
    public function error($err_code = 500)
    {

        $json = $this->errorData($err_code);

        $this->prepare($json['meta']);

        // Check response
        if (!in_array($this->request, [null, ''])) {

            return $this->pushView(
                fn () => $this->view($json['html_path']),

                fn () => $this->printJSON($err_code, $json['text_format'])
            );
        }

        $this->response = $this->responsePush;

        return $this->pushView(
            fn () => $this->response
                ->setBody($this->view($json['html_path']))
                ->send(),

            fn () => $this->printJSON($err_code, $json['text_format'])
        );
    }

    // Function to show error 404 page
    public function error404()
    {

        return $this->error(404);
    }
}

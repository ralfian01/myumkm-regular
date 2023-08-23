<?php

namespace App\API\V1;

use App\Controllers\BaseController;

class Error extends BaseController
{

    public function error404()
    {

        $responseBody['code'] = 404;
        $responseBody['body'] = [
            'code' => $responseBody['code'],
            'status' => 'INVALID_PATH',
            'description' => 'The path you are looking for is not available'
        ];


        $response = service('response');
        $response
            ->setContentType('application/json')
            ->setStatusCode($responseBody['code'])
            ->setBody(json_encode($responseBody['body'], JSON_PRETTY_PRINT));

        return $response;
    }
}

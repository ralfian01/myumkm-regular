<?php

namespace App\API;

use App\Controllers\ControllerPreProcessor;
use App\API\Library\Payload;

/**
 * The APIController provides a place where
 * the payload is processed by making use of the Payload class
 */

class APIController extends ControllerPreProcessor
{

    public object $payloadHandler;

    public function __construct()
    {

        $this->payloadHandler = new Payload();
    }

    // Error statuses
    private $errStatus = [
        400 => [
            'status' => 'BAD_REQUEST',
            'description' => 'Bad Request'
        ],
        401 => [
            'status' => 'UNAUTHORIZED',
            'description' => 'You do not have authorization to access this resource'
        ],
        403 => [
            'status' => 'FORBIDDEN',
            'description' => 'You are prohibited from accessing these resources'
        ],
        404 => [
            'status' => 'NOT_FOUND',
            'description' => 'URL not available'
        ],
        409 => [
            'status' => 'CONFLICT',
            'description' => 'Data already exists in the system'
        ],
        500 => [
            'status' => 'INTERNAL_ERROR',
            'description' => 'There is an error in internal server'
        ]
    ];

    /**
     * Function to set error status
     * 
     * @param string|int $code
     * @param array $status
     * @return void
     */
    private $setErrorStatus = false;
    public function setErrorStatus($code, $status)
    {

        foreach ($status as $key => $val) {
            $this->errStatus[$code][$key] = $val;
        }

        $this->setErrorStatus = true;
        return $this;
    }

    /**
     * Function contains json template to provide error response
     * 
     * @param int $code Http code
     * @param array $detail Error detail in object
     * @param array $ovr_error Override error status and description
     * @return void
     */
    public function error(
        $code = 400,
        $detail = []
    ) {

        $this->setErrorStatus = false;

        $code = intval($code);
        $json = [
            'code' => $code,
            'status' => $this->errStatus[$code]['status'],
            'description' => $this->errStatus[$code]['description'],
            'error_detail' => $detail
        ];

        // Apply CORS
        Parent::CORS();

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($code)
            ->setBody(json_encode($json, JSON_PRETTY_PRINT));
    }

    // Respond statuses
    private $respondStatus = [
        200 => [
            'status' => 'SUCCESS',
        ],
        201 => [
            'status' => 'CREATED',
        ],
        202 => [
            'status' => 'ACCEPTED',
        ],
        204 => [
            'status' => 'NO_CONTENT',
        ],
    ];

    /**
     * Function contains json template response
     * 
     * @param array|int $data_or_code
     * @param array $data
     * @return void
     */
    public function respond($data_or_code, $data = [])
    {

        $code = is_int($data_or_code) ? $data_or_code : 200;
        $data = is_array($data_or_code) ? $data_or_code : $data;

        // Apply CORS
        Parent::CORS();

        return $this->response
            ->setContentType('application/json')
            ->setStatusCode(200)
            ->setBody(json_encode(
                [
                    'code' => $code,
                    'status' => $this->respondStatus[$code]['status'],
                    'data' => $data
                ],
                JSON_PRETTY_PRINT
            ));
    }


    /** Function to return payload array based on request method and content type
     * This method can only return a payload array in non-file form 
     * 
     * @return array
     */
    public function getPayload()
    {

        // If request payload is json
        if ($this->request->is('json')) return $this->request->getJSON(true);

        $contentType = $this->request->header('content-type');
        $requestMethod = strtoupper($this->request->getServer('REQUEST_METHOD'));

        switch ($requestMethod) {

            case 'GET':

                // GET method only support x-www-form-urlencoded and common content type
                if (strpos($contentType, 'x-www-form-urlencoded') >= 0)
                    return $this->request->getRawInput();

                return $this->request->getPostGet();
                break;
            case 'POST':

                // POST method support form-data, x-www-form-urlencoded, and common content type
                return $this->request->getGetPost();
                break;
            case 'PUT':
            case 'PATCH':
            case 'DELETE':

                // PUT & PATCH method support form-data, x-www-form-urlencoded, and common content type
                if (strpos($contentType, 'form-data') >= 0) {

                    $formData = file_get_contents('php://input');
                    return Payload::parseFormData($formData);
                }

                if (strpos($contentType, 'x-www-form-urlencoded'))
                    return $this->request->getRawInput();
                break;
        }

        return [];
    }

    /** Function to return an array of files sent in formdata
     * This method can only check files in formdata format 
     * and with http post, put, or patch methods 
     * 
     * @param string $fileName Name of file
     * 
     * @return array
     */
    public function getFile($fileName = null)
    {

        $contentType = $this->request->header('content-type');
        $requestMethod = strtoupper($this->request->getServer('REQUEST_METHOD'));

        if (strpos($contentType, 'form-data') >= 0) {

            switch ($requestMethod) {

                case 'POST':

                    if ($fileName != null) return $this->request->getFile($fileName);
                    return $this->request->getFiles();
                    break;
                case 'PUT':
                case 'PATCH':
                case 'DELETE':

                    $formData = file_get_contents('php://input');
                    $data = Payload::parseFormDataFile($formData);

                    return $data;
                    break;
            }
        }

        return [];
    }
}

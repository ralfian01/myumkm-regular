<?php

namespace App\API\V1\Auth\Account\ResetPass;

use App\API\V1\Auth\Account\ResetPass\ResetBase;
use App\Models\Account;
use App\Models\FVA;
use stdClass;
use Config\Services;

class Reset extends ResetBase
{

    public function __construct()
    {

        $this->res = service('response');
        $this->accData = new Account();
        $this->fvaData = new FVA();

        $this->rec = new stdClass();

        // Send email template
        $this->mailTemp = Services::email();

        if (!strpos(base_url(), 'localhost')) {

            // Send main initialize
            $this->mailTemp->initialize([
                'SMTPHost' => getenv('SMTP_HOST'),
                'SMTPPort' => getenv('SMTP_PORT'),
                'SMTPUser' => getenv('SMTP_USER'),
                'SMTPPass' => getenv('SMTP_PASS'),
                'SMTPCrypto' => getenv('SMTP_CRYPTO'),
                'protocol' => getenv('SMTP_PROTOCOL'),
                'mailType' => getenv('SMTP_MAILTYPE'),
                'SMTPAuth' => getenv('SMTP_AUTH'),
                'fromEmail' => getenv('SMTP_MAIL'),
                'fromName' => getenv('SMTP_NAME')
            ]);
        }
    }

    ### Main function to be called
    public function index()
    {

        return $this->payloadChecker();
    }

    ### Check payload
    public function payloadChecker()
    {

        $payload = [
            'utm_code' => $this->request->getPost('utm_code'),
            'password' => $this->request->getPost('password')
        ];

        // Check payload isset
        if (
            $payload['utm_code'] != null
            && $payload['utm_code'] != null
        ) {

            if (is_string_base64($payload['password'])) {

                // Get UTM Data
                $utmData = $this->getUTMData($payload['utm_code']);
                if ($utmData == null) return $this->responses(4);

                // Check account data from utm email
                $accountData = $this->accData->allData([
                    'email' => $utmData['email']
                ]);
                if ($accountData == null) return $this->responses(5);

                // Recycle account data
                $this->rec->accountData = $accountData[0];

                return $this->updateData();
            } else {

                return $this->responses(3);
            }
        } else {

            return $this->responses(2, $payload);
        }
    }

    ### Update account password 
    public function updateData($email = null)
    {

        $payload = [
            'utm_code' => $this->request->getPost('utm_code'),
            'password' => base64_decode($this->request->getPost('password'))
        ];

        // Account data
        $accountData = $this->rec->accountData;

        // Set account status to reset pass mode
        $updateStatus = $this->accData->update(
            $accountData['id'],
            [
                'password' => hash('sha256', $payload['password']),
                'status' => '',
            ]
        );

        if (!$updateStatus) return $this->responses();

        // Send email
        if (!strpos(base_url(), 'localhost')) {

            $htmlBody = view(
                'Mailing/account/auth/resetSuccess',
                [
                    'receiver' => [
                        'email' => $accountData['email'],
                        'name' => $accountData['name']
                    ],
                    'sender' => [
                        'name' => getenv('SMTP_NAME'),
                        'email' => getenv('SMTP_MAIL')
                    ],
                    'additionalData' => [
                        'reset_time' => date('Y')
                    ]
                ]
            );

            $send = $this->mailTemp
                ->setTo($accountData['email'])
                ->setSubject('Update password berhasil')
                ->setMessage($htmlBody)
                ->send();

            if (!$send) {

                $this->mailTemp->printDebugger();
            }
        }

        return $this->responses(1, []);
    }

    public function responses($code = '', $data = '')
    {

        $resBody = [
            'code' => 500,
            'body' => [
                'code' => 500,
                'status' => 'INTERNAL_ERROR',
                'description' => 'There is error in internal server'
            ]
        ];

        switch ($code) {

            case 1:

                $resBody['code'] = 200;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'status' => 'SUCCESS',
                    'data' => $data
                ];
                break;

            case 2:
            case 3:

                $resBody['code'] = 400;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'status' => 'INVALID_API_PARAMETER',
                ];

                if ($code == 2) {

                    $resBody['body']['description'] = 'There are parameters that must be filled';
                    $resBody['body']['detail'] = [
                        'request' => [
                            'parameter' => [
                                'utm_code' => isset($data['utm_code']) ? $data['utm_code'] : null,
                                'password' => isset($data['password']) ? $data['password'] : null
                            ]
                        ]
                    ];
                } else if ($code == 3) {

                    $resBody['body']['description'] = 'Parameters are parsed the wrong way';
                }
                break;

            case 4:

                $resBody['code'] = 404;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'status' => 'INVALID_UTM_CODE',
                    'description' => 'UTM Code is invalid or expired'
                ];
                break;

            case 5:

                $resBody['code'] = 404;
                $resBody['body'] = [
                    'code' => $resBody['code'],
                    'status' => 'DATA_NOT_FOUND',
                    'description' => 'Account data not found'
                ];
                break;

            default: // Do nothing
                break;
        }

        $this->res
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->res;
    }
}
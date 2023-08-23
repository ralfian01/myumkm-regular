<?php

namespace App\API\V1\Auth\Account\ResetPass;

use App\API\V1\Auth\Account\ResetPass\ResetBase;
use App\Models\Account;
use stdClass;
use Config\Services;

class Request extends ResetBase
{

    public function __construct()
    {

        $this->res = service('response');
        $this->accData = new Account();

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
            'email' => $this->request->getPost('email')
        ];

        // Check payload isset
        if ($payload['email'] != null) {

            if (is_string_base64($payload['email'])) {

                // Check account availability from by email
                $accountData = $this->accData->allData([
                    'email' => strtolower(base64_decode($payload['email']))
                ]);
                if ($accountData == null) return $this->responses(4);

                // Recycle accoutn data
                $this->rec->accountData = $accountData[0];

                return $this->requestReset();
            } else {

                return $this->responses(3);
            }
        } else {

            return $this->responses(2, $payload);
        }
    }

    ### Request reset password by account email
    public function requestReset()
    {

        // Account data
        $accountData = $this->rec->accountData;

        $expiredDate = appendMin(date('Y-m-d H:i:s'), 45); // 45 Minutes

        // Update account to reset mode
        $updateStatus = $this->accData->update(
            $accountData['id'],
            [
                'status' => json_encode([
                    'code' => 'RESET_PASSWORD',
                    'expired_date' => $expiredDate
                ])
            ]
        );

        if (!$updateStatus) return $this->responses();

        $utmCode = $this->getUTMCode(
            $accountData['email'],
            $expiredDate
        );

        $resetURL = accountURL('reset_password?utm_time=' . urlencode(date('Y-m-d H:i:s')) . '&utm_code=' . $utmCode);

        // Send email
        if (!strpos(base_url(), 'localhost')) {

            $htmlBody = view(
                'Mailing/account/auth/resetPass',
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
                        'request_time' => date('Y'),
                        'reset_pass_url' => $resetURL
                    ]
                ]
            );

            $send = $this->mailTemp
                ->setTo($accountData['email'])
                ->setSubject('Reset password')
                ->setMessage($htmlBody)
                ->send();

            if (!$send) {

                $this->mailTemp->printDebugger();
            }
        }

        $rtData = [];

        if ($_ENV['CI_ENVIRONMENT'] != 'production') {

            $rtData['url'] = $resetURL;
        }

        return $this->responses(1, $rtData);
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
                                'email' => isset($data['email']) ? $data['email'] : null
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
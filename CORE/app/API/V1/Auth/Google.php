<?php

namespace App\API\V1\Auth;

use App\Controllers\BaseController;
use stdClass;

class Google extends BaseController
{

    public function __construct()
    {

        $this->res = service('response');
        $this->accData = new \App\Models\Account();
    }

    public function authenticate()
    {

        $resBody = [
            'code' => 500,
            'body' => [
                'code' => 500,
                'status' => 'INTERNAL_ERROR'
            ]
        ];

        $redirectUrl = apiURL('auth/google');

        $goClient = new \Google\Client();
        $goClient->setAuthConfig(APPPATH . 'ThirdParty/google_oauth2.json');
        $goClient->addScope('profile');
        $goClient->addScope('email');
        $goClient->setRedirectUri($redirectUrl);

        // Get callback data
        $code = $this->request->getGet('code');

        if ($code != null) {

            $token = $goClient->fetchAccessTokenWithAuthCode($code);

            if (isset($token['access_token'])) {

                $goService = new \Google\Service\Oauth2($goClient);
                $goAccData = $goService->userinfo->get();

                // Check account by email
                $accountData = $this->accData->checkAccountByEmail($goAccData->email);

                if ($accountData != null) {

                    // Get email and password and then redirect to account url
                    redirect()->to(apiUrl('accounts/' . base64_encode("$accountData[email]:$accountData[password]")));
                } else {

                    // Code
                }
            }
        } else {

            return redirect()->to($goClient->createAuthUrl());
        }

        $this->res
            ->setContentType('application/json')
            ->setStatusCode($resBody['code'])
            ->setBody(json_encode($resBody['body'], JSON_PRETTY_PRINT));

        return $this->res;
    }
}

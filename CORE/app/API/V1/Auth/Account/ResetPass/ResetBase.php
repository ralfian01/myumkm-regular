<?php

namespace App\API\V1\Auth\Account\ResetPass;

use App\Controllers\BaseController;
use App\Models\Account;

class ResetBase extends BaseController
{

    // Function to produce UTM Code
    public function getUTMCode(
        $email = null,
        $expDate = null
    ) {

        $email = base64_encode($email);
        $expDate = base64_encode($expDate);

        // Start make token
        $json = json_encode([
            'email' => $email,
            'exp_date' => $expDate
        ]);

        $token = base64_encode($json);
        $token .= '.' . hash('sha256', $json . '.' . $_ENV['ONE_TIME_SECRET_TOKEN']);

        return $token;
    }

    // Function to verify UTM Code
    public function verifyUTMCode($utmCode = null)
    {

        if ($utmCode == null) return false;

        // Start verify the token
        $json = base64_decode(explode('.', $utmCode)[0]);

        $checkToken = hash('sha256', $json . '.' . $_ENV['ONE_TIME_SECRET_TOKEN']);

        // Check utm expired date
        $expDate = base64_decode(json_decode($json, true)['exp_date']);

        // Check from account data
        $account = new Account();
        $accountData = $account->allData([
            'email' => base64_decode(json_decode($json, true)['email'])
        ]);

        if ($accountData == null) return false;

        $accountData = $accountData[0];

        if (
            !isset($accountData['status'])
            || !isset($accountData['status']['code'])
            || $accountData['status']['code'] != 'RESET_PASSWORD'
        ) return false;

        if (
            $expDate >= date('Y-m-d H:i:s')
            && $checkToken == explode('.', $utmCode)[1]
        ) return true;

        return false;
    }

    // Function to get UTM Data
    public function getUTMData($utmCode = null)
    {

        if ($utmCode == null) return false;

        // Verify UTM
        if (!$this->verifyUTMCode($utmCode)) return null;

        $json = json_decode(base64_decode(explode('.', $utmCode)[0]), true);

        $json['email'] = base64_decode($json['email']);
        $json['exp_date'] = base64_decode($json['exp_date']);

        return $json;
    }
}

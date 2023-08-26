<?php

namespace App\Models;

use CodeIgniter\Model;

class AppManifest extends Model
{

    protected $table            = 'myu_app_manifest';
    protected $primaryKey       = 'manifest_code';

    protected $returnType       = 'array';
    protected $allowedFields    = ['manifest_value'];
    protected $useAutoIncrement = false;
    protected $skipValidation   = true;
    protected $useTimestamps    = false;

    // Function to get payment method
    public function paymentMethodOption($method = null)
    {

        $result = $this
            ->select("
                myu_app_manifest.manifest_value
            ")
            ->where("myu_app_manifest.manifest_code", "PAYMENT_METHOD");

        $result = $this->find();

        if ($result == null) return null;

        $result = json_decode($result[0]['manifest_value'], true);

        // Parse the column that has JSON value
        foreach ($result as $key => $val) {

            $val['method'] = $key;

            $result[$key] = $val;
        }

        if (isset($method) && !empty($method)) $result = $result[$method];

        return $result;
    }

    // Functino to get owner contact info
    public function ownerContact($method = null)
    {

        $result = $this
            ->select("
                myu_app_manifest.manifest_value
            ")
            ->where("myu_app_manifest.manifest_code", "OWNER_CONTACT");

        $result = $this->find();

        if ($result == null) return null;

        $result = json_decode($result[0]['manifest_value'], true);

        // Parse the column that has JSON value
        foreach ($result as $key => $val) {

            $result[$key] = $val;
        }

        if (isset($method) && !empty($method)) $result = $result[$method];

        return $result;
    }

    public function uploadSetting()
    {

        // Page Setting: General
        return json_decode($this->find('UPLOAD_SETTING')['manifest_value'], true);
    }

    public function googleAPI2OAuth()
    {

        // Google login api 2 oauth
        return json_decode($this->find('AUTHENTICATION')['manifest_value'], true)['google_api_2_oauth'];
    }
}

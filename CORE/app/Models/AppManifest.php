<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\TextUI\XmlConfiguration\Variable;
use stdClass;

class AppManifest extends Model
{

    protected $table            = 'app_manifest';
    protected $primaryKey       = 'manifest_code';

    protected $returnType       = 'array';
    protected $allowedFields    = ['manifest_value'];
    protected $useAutoIncrement = false;
    protected $skipValidation   = true;
    protected $useTimestamps    = false;

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

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Throwable;
use stdClass;

class ControllerPreProcessor extends BaseController
{

    protected string $basePath = '';
    protected array $addonObj = [];

    // Function to make initial meta data
    private function initMeta()
    {

        return [
            'title' => 'MyUMKM.com',
            'description' => 'Profil MyUMKM.com',
            'img_url' => cdnURL('logo/original/origin_full_v1.svg'),
            'current_url' => (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            'keywords', 'website, company profile',
            'site_name' => 'MyUMKM.com',
            'site_icon' => cdnURL('logo/original/origin_full_v1.svg')
        ];
    }

    // Function to combine all objects to one zobject
    private function combineObject()
    {

        $data = [
            'base_path' => $this->basePath,
            'meta' => $this->initMeta()
        ];

        // Insert addon data object to main controller object
        if (isset($this->addonObj)) {

            foreach ($this->addonObj as $key => $val) {

                // Insert if addonObj key is not "meta"
                if ($key != 'meta') $data[$key] = $val;
            }

            if (isset($this->addonObj['meta']) && !in_array($this->addonObj['meta'], [null, ''])) {

                foreach ($this->addonObj['meta'] as $key => $value) {

                    $data['meta'][$key] = $value;
                }
            }
        }

        return $data;
    }

    // Function to set base path of view controller
    public function prepBasePath($base_path = '')
    {

        $this->basePath = $base_path;
        return $this;
    }

    // Function to override default meta data
    public function prepMeta($meta = [])
    {

        if (!isset($this->addonObj['meta']) || empty($this->addonObj['meta']))
            $this->addonObj['meta'] = [];

        $this->addonObj['meta'] = $this->combineArray($this->addonObj['meta'], $meta);

        return $this;
    }

    // Function to get meta data
    public function getMeta($metaKey = '')
    {

        if (!isset($this->addonObj['meta']) || empty($this->addonObj['meta']))
            $this->prepMeta($this->initMeta());

        if (isset($this->addonObj['meta'][$metaKey]))
            return $this->addonObj['meta'][$metaKey];

        return null;
    }

    // Function to get method response, process it and return it to main controller object
    public function prepResponse($response = [])
    {

        // There is still no data in $response to be processed
        return $this;
    }

    // Function to get addon data process it and return it to main controller object
    public function prepAddon($addon = [])
    {

        if (!isset($this->addonObj['meta'])) {

            $this->addonObj = $addon;
        } else {

            $this->addonObj = $this->combineArray($this->addonObj, $addon);
        }

        return $this;
    }

    // Function to get addon data
    public function getAddon($addonKey = '')
    {

        if (isset($this->addonObj[$addonKey])) return $this->addonObj[$addonKey];
        return null;
    }

    // Show page
    public function view($path = '')
    {

        // Set header rule
        $this->CORS();

        $data = $this->combineObject();

        return view(
            "{$data['base_path']}/{$path}",
            $data
        );
    }


    // Function to handle error
    public function tryCatch($function)
    {

        // Compile all arguments
        $param = $this->compileArgs(func_get_args());

        if ($_ENV['CI_ENVIRONMENT'] == 'production') {

            try {

                if (!is_callable($function)) return $param;

                return $function($param);
            } catch (Throwable $th) {

                $err = new Error($this->response, $this->request);
                $err->error(500);
            }
        } else {

            if (!is_callable($function)) return $param;

            return $function($param);
        }
    }

    /**
     * @param Function  $scFunction     Callback function triggered when process success
     * @param Function  $errFunction    Callback function triggered when process error or failed
     * 
     * @todo callback function $errFunction must be defined if you want to do something to client when they are not authenticated
     */

    // Function to handle authentication
    public function authHandler(
        $scFunction,
        $errFunction
    ) {

        /*
            !!! Note:
            callback function $errFunction must be defined
            if you want to do something to client when they
            are not authenticated
        */

        // Compile all arguments
        $param = $this->compileArgs(func_get_args());

        $err = new Error($this->response, $this->request);

        // If client not authenticated
        if (isset($this->request->auth)) {

            $param['auth'] = $this->request->auth;
            $param['uri_segment'] = $_SERVER['REQUEST_URI'];

            if (!$this->request->auth->status) {

                if (is_callable($errFunction)) return $errFunction($param);
                return $err->error(500);
            }

            // If client authenticated
            if (is_callable($scFunction)) return $scFunction($param);
            return $err->error(500);
        } else {

            // If client not authenticated
            if (is_callable($scFunction)) return $scFunction($param);

            return $err->error(500);
        }
    }

    // Function to compile all arguments
    private function compileArgs($args = [])
    {

        $param = [];

        foreach ($args as $key => $val) {

            if (is_array($val)) {

                foreach ($val as $vKey => $vVal) {

                    $param[$vKey] = $vVal;
                }
            } else {

                if (is_array($val) || is_string($val)) array_push($param, $val);
            }
        }

        return $param;
    }

    /**
     * Function to combine 2 arrays
     * @param array $dest_array Array destination
     * @param array $enter_array Array origin
     * @return array
     */
    public function combineArray($dest_array, $enter_array)
    {

        foreach ($enter_array as $key => $val) {

            $dest_array[$key] = $val;
        }

        return $dest_array;
    }

    // Function to set header rule
    public function CORS()
    {

        $origin = [
            'http://admin.' . XHOSTNAME,
            'https://admin.' . XHOSTNAME,
            'http://' . XHOSTNAME,
            'https://' . XHOSTNAME,
            'http://api.' . XHOSTNAME,
            'https://api.' . XHOSTNAME,
            'http://accounts.' . XHOSTNAME,
            'https://accounts.' . XHOSTNAME,
        ];

        if ($_ENV['CI_ENVIRONMENT'] == 'development') {

            array_push(
                $origin,
                'http://localhost:' . LOCAL_PORT,
                'http://admin.localhost:' . LOCAL_PORT,
                'http://accounts.localhost:' . LOCAL_PORT,
                'http://pos.localhost:' . LOCAL_PORT
            );
        }

        if (isset($_SERVER['HTTP_ORIGIN'])) {

            if (in_array($_SERVER['HTTP_ORIGIN'], $origin)) {

                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            }
        }

        $header = ['Authorization', 'Content-Type', 'X-Requested-With'];

        $stringHeader = null;

        foreach ($header as $key => $value) {

            $stringHeader = $stringHeader == null ? $value : $stringHeader . ', ' . $value;
        }

        header('Access-Control-Allow-Headers: ' . $stringHeader);
        header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, PUT, OPTIONS');
    }

    // Save image
    public function saveImages(
        $source,
        $newName = false,
        $square = [
            'status' => false,
            'position' => 'START' || 'CENTER' || 'END'
        ]
    ) {

        $image = \Config\Services::image();

        // Upload folder target
        $folder = 'image/' . date('Ymd') . '/';

        // Rename image file
        if (
            !$newName
            || in_array($newName, [null, ''])
        ) {

            if (in_array(strtolower(last_item(explode('.', $source->getName()))), ['png', 'jpg', 'jpeg'])) {

                $newName = 'HW_' . date('Hisms') . '.' . strtolower(last_item(explode('.', $source->getName())));
            } else {

                $newName = 'HW_' . date('Hisms') . '.' . strtolower(last_item(explode('/', $source->getClientMimeType())));
            }
        }

        // Image properties
        $imgProp = $image->withFile($source)->getFile()->getProperties(true);

        // Upload setting
        $db = \Config\Database::connect();
        $builder = $db->table('app_manifest');
        $builder
            ->select('*')
            ->where('manifest_code', 'UPLOAD_SETTING');

        $result = $builder->get()->getResult();

        if ($result != null) {

            $upSetting = json_decode(decode_stdClass($result[0])['manifest_value'], true);
        } else {

            $upSetting['image'] = [
                'max_size' => [
                    'unit' => 'mb',
                    'size' => '1',
                ],
                'compress' => '0.5'
            ];
        }

        $imgDim = [
            'width' => $imgProp['width'],
            'height' => $imgProp['height']
        ];

        // If image size too big
        if ($source->getSizeByUnit($upSetting['image']['max_size']['unit']) > $upSetting['image']['max_size']['size']) {

            // Resize image to smaller size
            $imgDim['width'] *= $upSetting['image']['compress'];
            $imgDim['height'] *= $upSetting['image']['compress'];
        }

        // Create folder
        if (!file_exists(WRITEPATH . 'uploads/' . $folder)) mkdir(WRITEPATH . 'uploads/' . $folder);

        $return = new stdClass();

        if ($square['status']) {

            // Crop image to square size 
            $sqSize = 0;

            // Get image dimension from real size
            if ($imgProp['width'] >= $imgProp['height']) {

                $sqSize = $imgProp['height'];

                if ($square['position'] == 'START') {

                    $square['position'] = 'left';
                } else if ($square['position'] == 'END') {

                    $square['position'] = 'right';
                } else {

                    $square['position'] = 'center';
                }
            } else {

                $sqSize = $imgProp['width'];

                if ($square['position'] == 'START') {

                    $square['position'] = 'top';
                } else if ($square['position'] == 'END') {

                    $square['position'] = 'bottom';
                } else {

                    $square['position'] = 'center';
                }
            }

            // Resize, crop & fit image
            $return->status = $image
                ->withFile($source)
                ->flatten(255, 255, 255)
                ->fit($sqSize, $sqSize, $square['position'])
                ->resize(intval($imgDim['width']), intval($imgDim['height']), true, 'height')
                ->save(WRITEPATH . 'uploads/' . $folder . $newName);
        } else {

            // Resize image
            $return->status = $image
                ->withFile($source)
                ->flatten(255, 255, 255)
                ->resize(intval($imgDim['width']), intval($imgDim['height']), true, 'height')
                ->save(WRITEPATH . 'uploads/' . $folder . $newName);
        }

        $return->path = $folder . $newName;

        return $return;
    }
}

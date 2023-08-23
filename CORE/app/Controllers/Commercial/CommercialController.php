<?php

namespace App\Controllers\Commercial;

use App\Controllers\ControllerPreProcessor;
use App\Models\Catalog\CatalogCategory;

class CommercialController extends ControllerPreProcessor
{

    // Decide to authenticate client or not
    private $unauthorizedScheme;

    protected
        $ctrPrep;
    protected $base_path = '_commercial';

    public function __construct($modData = [])
    {

        $this->unauthorizedScheme = function ($cbPar = []) {

            if (method_exists($this, '__unauthorizedScheme'))
                return $this->__unauthorizedScheme($cbPar);
        };

        if (
            isset($modData['unauthorizedScheme'])
            && is_callable($modData['unauthorizedScheme'])
        ) $this->unauthorizedScheme = $modData['unauthorizedScheme'];

        return $this;
    }

    // Default unauthorized scheme
    private function __unauthorizedScheme($cbPar = [])
    {

        // $err = new Error($this->request, $this->response);
        // return $err->error(500);
    }

    // Function to prepare controller
    public function prepare(
        $meta = [],
        $addon = []
    ) {

        if (!isset($addon)) $addon = [];

        $this
            ->prepBasePath($this->base_path)
            ->prepResponse($this->response)
            ->prepAddon($addon);

        return $this;
    }

    // Show page
    public function viewPage(
        $path = '',
        $addonData = []
    ) {

        $addonData = [
            'phone_number' => '6289652330969',
            'wa_number' => '6289652330969',
            'email' => 'astutisuprih77@gmail.com',
            'address' => 'Jl. Jatipadang Raya, Gg. Yusuf Rt.006/Rw.009 No.101, \r\nKel. Jatipadang, Kec. Pasar Minggu, \r\nJakarta Selatan 12540',
            'social_media' => [
                'facebook' => 'https://www.facebook.com/profile.php?id=100085662048429&mibextid=ZbWKwL',
                'instagram' => 'https://instagram.com/dapurfirdaus10?igshid=ZDdkNTZiNTM=',
                'twitter' => '',
                'tiktok' => ''
            ],
            'payment_method' => [
                [
                    'method' => 'BRI',
                    'name' => 'Suprih Astuti',
                    'number' => '142501003769531'
                ]
            ],
            'catalog_category' => $this->catCategory(1),
            'seller_name' => 'Dapur Firdaus',
            'current_url' => $this->getMeta('current_url')
        ];

        return $this->authHandler(
            function ($cbPar) {

                $this->prepare(null, $cbPar['addonData']);
                return $this->view($cbPar['path']);
            },
            $this->unauthorizedScheme,
            ['addonData' => $addonData],
            ['path' => $path]
        );
    }

    // 404 page for this package
    public function error404()
    {

        $this->prepare([
            'title' => 'Halaman tidak ditemukan',
            'description' => 'Halaman yang anda cari mungkin sudah dihapus atau sudah diganti oleh pemilik website.'
        ]);

        return $this->view('error/404');
    }


    ### Function to process data from database
    // Process catalog category
    private function catCategory($condition = 1)
    {

        $catCategory = new CatalogCategory();

        switch ($condition) {

            case 1:

                return $catCategory->all([
                    'status' => 'SHOW'
                ]);
                break;
        }

        return null;
    }
}

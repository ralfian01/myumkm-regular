<?php

namespace App\Controllers\Commercial;

use App\Controllers\ControllerPreProcessor;
use App\Models\Catalog\CatalogCategory;
use App\Models\AppManifest;

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
    public function prepare($meta = [], $addon = [])
    {

        if (!isset($meta)) $meta = [];
        if (!isset($addon)) $addon = [];

        // Combine meta with initial meta
        $meta = $this->combineArray($this->initMeta(), $meta);

        // Combine addon with initial addon
        $addon = $this->combineArray($this->initAddon(), $addon);

        $this
            ->prepBasePath($this->base_path)
            ->prepResponse($this->response)
            ->prepMeta($meta)
            ->prepAddon($addon);

        return $this;
    }

    // Function to set initial addon meta
    private function initMeta()
    {

        $ownerContact = (new AppManifest())->ownerContact();

        return [
            'title' => $ownerContact['site_name'],
            'site_name' => $ownerContact['site_name'],
            'description' => $ownerContact['site_name'],
        ];
    }

    // Function to set initial addon data
    private function initAddon()
    {

        $ownerContact = (new AppManifest())->ownerContact();

        return [
            'phone_number' => $ownerContact['phone_number'],
            'wa_number' => $ownerContact['social_media']['whatsapp']['username'],
            'email' => $ownerContact['email'],
            'address' => $ownerContact['address'],
            'social_media' => $ownerContact['social_media'],
            'marketplace' => $ownerContact['marketplace'],
            'catalog_category' => $this->catCategory(1),
            'seller_name' => $ownerContact['site_name'],
            'current_url' => $this->getMeta('current_url')
        ];
    }

    // Show page
    public function viewPage($path = '', $addonData = [])
    {

        return $this->authHandler(
            function ($cbPar = null) {

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

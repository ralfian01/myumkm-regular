<?php

namespace App\Controllers\Admin;

use App\Controllers\ControllerPreProcessor;

class AdminController extends ControllerPreProcessor
{

    // Decide to authenticate client or not
    private $unauthorizedScheme;

    protected
        $ctrPrep;
    protected $base_path = '_admin';

    public function __construct($modData = [])
    {

        $this->unauthorizedScheme = function ($cbPar = []) {

            if (method_exists($this, '__defaultUnauthorizedScheme'))
                return $this->__defaultUnauthorizedScheme($cbPar);
        };

        if (
            isset($modData['unauthorizedScheme'])
            && is_callable($modData['unauthorizedScheme'])
        ) $this->unauthorizedScheme = $modData['unauthorizedScheme'];

        return $this;
    }

    // Default unauthorized scheme
    private function __defaultUnauthorizedScheme($cbPar = [])
    {

        // Get full URL
        $current_url = urlencode($this->getMeta('current_url'));

        if ($cbPar['uri_segment'] != '/login') return redirect()->to(adminURL('login?continue=' . $current_url));
    }


    // Function to prepare controller
    public function prepare(
        $meta = [],
        $addon = []
    ) {

        if (!isset($meta)) $meta = [];
        if (!isset($addon)) $addon = [];

        // Combine addon with initial addon
        $addon = $this->combineArray($this->initAddon(), $addon);

        $this
            ->prepBasePath($this->base_path)
            ->prepResponse($this->response)
            ->prepAddon($addon);

        return $this;
    }

    // Function to set initial meta data
    private function initAddon()
    {

        return [];
    }

    // Show page
    public function viewPage(
        $path = '',
        $addonData = []
    ) {

        $successFunction = function ($cbPar) {

            $cbPar['addonData']['username'] = $this->request->auth->data['username'];

            $this->prepare(null, $cbPar['addonData']);

            return $this->view($cbPar['path']);
        };

        return $this->authHandler(
            $successFunction,
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
}

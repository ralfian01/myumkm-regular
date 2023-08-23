<?php

if (!function_exists('cdnURL')) {

    function cdnURL(
        string $segment = '',
        bool $encodeUrl = false // Don't encode the url path
    ) {

        // $cdnUrl = str_replace(['://'], '://cdn.', base_url());
        $cdnUrl = base_url('myu_cdn/');

        $segment = $encodeUrl ? base64_encode($segment) : $segment;

        return $cdnUrl . $segment;
    }
}

if (!function_exists('adminURL')) {

    function adminURL(
        string $segment = ''
    ) {

        // $url = str_replace(['://'], '://admin.', base_url());
        $url = base_url('admin/');

        return $url . $segment;
    }
}

if (!function_exists('accountURL')) {

    function accountURL(
        string $segment = ''
    ) {

        $url = str_replace(['://'], '://accounts.', base_url());

        return $url . $segment;
    }
}

if (!function_exists('apiURL')) {

    function apiURL(
        string $segment = ''
    ) {

        // $apiUrl = str_replace(['://'], '://api.', base_url());
        $url = base_url('myu_api/');

        return $url . $segment;
    }
}

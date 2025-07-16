<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

if (!function_exists('preView')) {
    function preView($key)
    {
        echo "<pre>";
        print_r($key);
        die;
        return;
    }
}
if (!function_exists('getCurrentDateTime')) {
    function getCurrentDateTime()
    {
        date_default_timezone_set('Asia/Calcutta');
        $date = date('Y-m-d h:i:s', time());
        return $date;
    }
}

if (!function_exists('descryptId')) {
    function descryptId($key)
    {
        return Crypt::decryptString($key);
    }
}

if (!function_exists('userROles')) {
    function userROles()
    {
        $roles = array(
            'admin' => "Admin",
            'user'  => "User",
        );
        return $roles;
    }
}


if (!function_exists("talukaStatus")) {
    function talukaStatus($key = null)
    {
        $talukaStatus = array(
            '1' => "Active",
            '2'  => "Inactive",
        );
        // return $talukaStatus;
        return isset($talukaStatus[$key]) ? $talukaStatus[$key] : $talukaStatus;
    }
}

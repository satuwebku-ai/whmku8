<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Dedicated client and admin hosts
    |--------------------------------------------------------------------------
    |
    | Keep these values as bare hostnames. Route groups bind each portal to
    | its own host while the public storefront continues to use APP_URL.
    |
    */

    'client_host' => env('CLIENT_DOMAIN', 'member.satucloudhosting.com'),
    'admin_host' => env('ADMIN_DOMAIN', 'pengelola.satucloudhosting.com'),
];

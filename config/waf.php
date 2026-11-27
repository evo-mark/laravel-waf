<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Country IP database
    |--------------------------------------------------------------------------
    |
    | Absolute path to the MaxMind-format (.mmdb) database used to resolve a
    | request's IP address to its country.
    |
    */

    'mmdb_path' => env('WAF_MMDB_PATH', storage_path('app/waf/countries-ip.mmdb')),

];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Master toggle
    |--------------------------------------------------------------------------
    | If disabled, calls will just log and return without hitting SQL.
    */
    'enabled' => env('EMAILQUEUE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Database connection & stored procedure
    |--------------------------------------------------------------------------
    */
    'connection' => env('EMAILQUEUE_DB_CONNECTION', 'adhoc'),   // sqlsrv connection name
    'procedure' => env('EMAILQUEUE_STORED_PROCEDURE', 'dbo.usp_MessageQueue_Add'),

    /*
    |--------------------------------------------------------------------------
    | Defaults
    |--------------------------------------------------------------------------
    */
    'default_from' => env('EMAILQUEUE_FROM', 'jvmanifest@jamaicaobserver.com'),
    'default_to' => env('EMAILQUEUE_TO', 'williamskt@jamaicaobserver.com'),
    'default_cc' => env('EMAILQUEUE_CC', ''),
    'default_bcc' => env('EMAILQUEUE_BCC', ''),

    'encoding' => env('EMAILQUEUE_ENCODING', 'UTF-8'),

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    | If log_channel is null, it will use the default Laravel log channel.
    */
    'log_channel' => env('EMAILQUEUE_LOG_CHANNEL', null),
];

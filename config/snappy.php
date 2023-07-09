<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timout:
    |    
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */
    
    'pdf' => [
        'enabled' => true,
        //'binary' => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"',
        'binary' => base_path('vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf'),
       //'binary' => 'vendor\h4cc\wkhtmltopdf-amd64\bin\wkhtmltopdf-amd64',

       'timeout' => false,
        'options' => [
            'margin-top'    => 45,
            'margin-right'  => 10,
            'margin-bottom' => 30,
            'margin-left'   => 10,
        ],
        'env'     => [],
    ],
    
    'image' => [
        'enabled' => true,
        //'binary' => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage"',
        'binary' => 'vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltoimage',
       // 'binary' => 'vendor\h4cc\wkhtmltoimage-amd64\bin\wkhtmltoimage-amd64',
        //https://www.nicesnippets.com/blog/generate-pdf-with-graph-using-snappy-in-laravel
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];

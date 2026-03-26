<?php

return [
    'cloud_url' => env('AWS_URL'),

    'status'      => [
        1 => "Active",
        0 => "Inactive",
    ],

    'level'       => [
        'admin' => 0,
        'user'  => 1,
    ],
    'quill_style' => "<style>.ql-align-center{text-align:center;}.ql-align-right{text-align:right;}</style>",
];

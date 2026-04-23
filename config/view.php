<?php

$viewsPath = storage_path('framework/views');

if (!is_dir($viewsPath)) {
    mkdir($viewsPath, 0777, true);
}

return [

    'paths' => [
        resource_path('views'),
    ],

    'compiled' => env('VIEW_COMPILED_PATH', $viewsPath),

];
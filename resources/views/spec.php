<?php
$spec = [
    'swagger' => '2.0',
    'info' => [
        'title' => 'Reflect API',
        'description' => 'Provides the documentation for the Reflect Public API',
        'version' => '0.1'
    ],
    'paths' => $paths,
    'definitions' => $definitions,
    'tags' => $tags
];
echo json_encode($spec);
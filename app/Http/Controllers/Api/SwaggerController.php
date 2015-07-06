<?php


namespace App\Http\Controllers\Api;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SwaggerController {

    public function getIndex() {

        return view('ui');
    }

    public function getDocs() {

        $paths = [];
        $definitions = [];
        $tags = ['auth'];

        $path_to_swagger_resources = base_path('resources/swagger');
        $di = new RecursiveDirectoryIterator($path_to_swagger_resources);

        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {

            if (ends_with($filename, '.php')) {

                if (str_contains($filename, '/paths/')) {

                    $paths = array_merge($paths, include $filename);
                }

                if (str_contains($filename, '/definitions/')) {

                    $definitions = array_merge($definitions, include $filename);

                }
            }
        }


        return view('spec', [
            'paths' => $paths,
            'definitions' => $definitions,
            'tags' => $tags
        ]);

    }
}
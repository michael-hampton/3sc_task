<?php

function load_classes() {

    $dir = __DIR__ . '/interfaces/';

    // do interfaces first
    foreach (scandir($dir) as $file)
    {
        if (in_array($file, array('.', '..')) || !preg_match("/.php$/i", $file))
        {
            continue;
        }

        require_once $dir . $file;
    }

    // now do the models
    foreach (scandir(__DIR__) as $file)
    {
        if (in_array($file, array('.', '..')) || !preg_match("/.php$/i", $file))
        {
            continue;
        }

        require_once __DIR__ . '/' . $file;
    }
}

load_classes();


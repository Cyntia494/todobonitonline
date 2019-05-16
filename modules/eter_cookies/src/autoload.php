<?php

spl_autoload_register(function ($className) {
	if (!class_exists($className)) {
        $file = dirname(__FILE__)."/lib/{$className}.php";
        if (file_exists($file)) {
            require_once $file;
        }
    }
});



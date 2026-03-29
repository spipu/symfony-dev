<?php

declare(strict_types=1);

require_once dirname(dirname(__DIR__)) . '/website/vendor/autoload.php';

spl_autoload_register(
    function (string $class): bool {
        $filename = dirname(__DIR__) . '/class/' . trim(str_replace('\\', '/', $class), '/') . '.php';
        if (is_file($filename)) {
            require_once $filename;
            return true;
        }

        return false;
    }
);

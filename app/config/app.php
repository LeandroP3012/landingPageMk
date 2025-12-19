<?php

// Ruta física absoluta del proyecto
define('ROOT_PATH', realpath(__DIR__ . '/../../'));

// URL base (funciona en localhost y hosting)
define(
    'BASE_URL',
    ($_SERVER['HTTP_HOST'] === 'localhost')
    ? '/landingPageMk'
    : ''
);

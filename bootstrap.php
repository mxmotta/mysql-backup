<?php

function env(string $env, mixed $alternative = NULL) : string
{
    return $_ENV[$env] ?? $alternative;
}

function config(string $config): mixed
{
    $config_file_name = explode('.', $config)[0];
    $config_file = require('config/' . $config_file_name . '.php');
    $dot = new \Adbar\Dot($config_file);
    return $dot->get(str_replace($config_file_name . '.', '', $config));
}

function now() : \Carbon\Carbon {
    return \Carbon\Carbon::now();
}

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

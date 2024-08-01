<?php

/**
 * Return value from enviroment variable
 * @param string $env
 * @param mixed $alternative
 * @return string
 */
function env(string $env, mixed $alternative = NULL): string
{
    return $_ENV[$env] ?? $alternative;
}

/**
 * Return value from config doted format
 * @param string $config
 * @return mixed
 */
function config(string $config): mixed
{
    $config_file_name = explode('.', $config)[0];
    $config_file = require('config/' . $config_file_name . '.php');
    $dot = new \Adbar\Dot($config_file);
    return $dot->get(str_replace($config_file_name . '.', '', $config));
}

/**
 * Create a new Carbon time formate for NOW
 * @return \Carbon\Carbon
 */
function now(): \Carbon\Carbon
{
    return \Carbon\Carbon::now();
}

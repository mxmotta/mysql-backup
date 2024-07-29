<?php

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
if (file_exists('.env')) {
    $dotenv->load();
}

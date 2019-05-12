<?php

const BASE_PATH = __DIR__ . '/..';
require BASE_PATH . '/vendor/autoload.php';

use Lemundo\Translator\Factory;
use Dotenv\Dotenv;
use Lemundo\Translator\Config;

$dotenv = Dotenv::create(BASE_PATH);
$dotenv->load();

$config = new Config(require BASE_PATH . '/config/config.php');

$factory = new Factory($config);

$database = new PDO($config->getDatabaseConnectionDsn());
$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$database->exec(file_get_contents(BASE_PATH . '/resources/install.sql'));

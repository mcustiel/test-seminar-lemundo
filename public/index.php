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

$factory->createApplication()->run();

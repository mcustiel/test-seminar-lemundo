<?php

namespace Lemundo\Translator;

class Config
{
    /** @var array */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getDatabaseConnectionDsn(): string
    {
        return $this->config['databaseDsn'];
    }

    public function isDebugMode(): bool
    {
        return $this->config['debugMode'];
    }

    public function getCachePath(): string
    {
        return BASE_PATH . \DIRECTORY_SEPARATOR . $this->config['cachePath'];
    }
}

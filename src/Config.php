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
        return $this->config['basePath'] . \DIRECTORY_SEPARATOR . $this->config['cachePath'];
    }
}

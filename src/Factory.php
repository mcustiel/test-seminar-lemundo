<?php

namespace Lemundo\Translator;

use FastRoute\Dispatcher;
use Lemundo\Translator\Persistence\Pdo\PdoConnection;
use Lemundo\Translator\Persistence\Pdo\PdoTranslations;
use Lemundo\Translator\Persistence\Translations;
use Lemundo\Translator\Ui\Controllers\LocalesController;
use Lemundo\Translator\Ui\Controllers\TranslationsController;
use Lemundo\Translator\Ui\Router\ControllerLocator;
use Lemundo\Translator\Ui\Router\Implementation\FastRouterHandler;
use Lemundo\Translator\Ui\Router\RequestDispatcher;

class Factory
{
    /** @var Config */
    private $config;

    /** @var PdoConnection */
    private $dbConnection;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function createDatabaseConnection(): PdoConnection
    {
        if ($this->dbConnection === null) {
            $this->dbConnection = new PdoConnection($this->config->getDatabaseConnectionDsn());
        }

        return $this->dbConnection;
    }

    public function createTranslationsPersistence(): Translations
    {
        return new PdoTranslations($this->createDatabaseConnection());
    }

    public function createRequestDispatcher(): RequestDispatcher
    {
        return new FastRouterHandler($this->createControllerLocator(), $this->createDispatcher());
    }

    public function createControllerLocator(): ControllerLocator
    {
        return new ControllerLocator(
            $this->createTranslationsController(),
            $this->createLocalesController()
        );
    }

    public function createTranslationsController(): TranslationsController
    {
        return new TranslationsController($this->createTranslationsPersistence());
    }

    public function createLocalesController(): LocalesController
    {
        return new LocalesController($this->createTranslationsPersistence());
    }

    public function createApplication(): Application
    {
        return new Application(
            $this->createRequestDispatcher(),
            $this->createDatabaseConnection()
        );
    }

    public function createDispatcher(): Dispatcher
    {
        return \FastRoute\simpleDispatcher(
            FastRouterHandler::createDispatcherCallable(),
            [
                'cacheFile'     => $this->config->getCachePath() . '/route.cache',
                'cacheDisabled' => $this->config->isDebugMode(),
            ]
        );
    }
}

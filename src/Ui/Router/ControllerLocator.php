<?php
namespace Lemundo\Translator\Ui\Router;

use Lemundo\Translator\Ui\Controllers\TranslationsController;
use Lemundo\Translator\Ui\Controllers\LocalesController;
use Lemundo\Translator\Ui\Controllers\Controller;

class ControllerLocator
{
    /** @var Controller[] */
    private $controllers;

    public function __construct(
        TranslationsController $translationsController,
        LocalesController $localesController
    ) {
       $this->controllers = [
            'translations' => $translationsController,
            'locales' => $localesController
       ];
    }

    public function locate(string $controller): Controller
    {
        if (!array_key_exists($controller, $this->controllers)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid controller identifier: %s', $controller)
            );
        }
        return $this->controllers[$controller];
    }
}

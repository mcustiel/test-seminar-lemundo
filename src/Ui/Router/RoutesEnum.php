<?php

namespace Lemundo\Translator\Ui\Router;

use InvalidArgumentException;

class RoutesEnum
{
    public const SET_TRANSLATION = 'translations::set';
    public const GET_TRANSLATION = 'translations::get';
    public const DELETE_TRANSLATION = 'translations::delete';
    public const LIST_TRANSLATIONS = 'translations::list';
    public const LIST_LOCALES = 'locales::list';

    private const ROUTE_SEPARATOR = '::';

    private const VALID_ROUTES = [
        self::SET_TRANSLATION,
        self::GET_TRANSLATION,
        self::DELETE_TRANSLATION,
        self::LIST_TRANSLATIONS,
        self::LIST_LOCALES,
    ];

    /** @var string */
    private $controller;

    /** @var string */
    private $method;

    public function __construct(string $route)
    {
        $this->ensureIsValidRoute($route);
        list($this->controller, $this->method) = explode(self::ROUTE_SEPARATOR, $route);
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    private function ensureIsValidRoute(string $route): void
    {
        if (!\in_array($route, self::VALID_ROUTES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid route: %s. Must be one of [%s]',
                    $route,
                    implode(', ', self::VALID_ROUTES)
                )
            );
        }
    }
}

<?php

namespace Lemundo\Translator\Ui\Router\Implementation;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Lemundo\Translator\Ui\Router\{RoutesEnum, RequestDispatcher, ControllerLocator};
use RuntimeException;
use Lemundo\Translator\Ui\Controllers\Controller;

class FastRouterHandler implements RequestDispatcher
{
    /** @var Dispatcher */
    private $dispatcher;
    /** @var ControllerLocator */
    private $controllerLocator;

    public function __construct(ControllerLocator $locator, Dispatcher $dispatcher)
    {
        $this->controllerLocator = $locator;
        $this->dispatcher = $dispatcher;
    }

    public static function createDispatcherCallable () : callable
    {
        return function (RouteCollector $collector) {
            $collector->addRoute('POST', '/locale/{locale}/translation', RoutesEnum::ADD_TRANSLATION);
            $collector->addRoute('GET', '/locale/{locale}/translation', RoutesEnum::LIST_TRANSLATIONS);
            $collector->addRoute('GET', '/locale/{locale}/translation/{translationId}', RoutesEnum::GET_TRANSLATION);
            $collector->addRoute('GET', '/locale', RoutesEnum::LIST_LOCALES);
        };
    }

    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response = (new Response())->withStatus(404);
                return $response;
            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(
                    sprintf(
                        'Method not allowed. Allowed methods for %s: %s',
                        $uri,
                        implode(', ', $routeInfo[1])
                    ),
                    405
                );
                break;
            case Dispatcher::FOUND:
                return $this->execiteRequestInController($routeInfo, $request);

            default:
                $response = (new Response())->withStatus(500);
        }
    }

    private function execiteRequestInController(
        array $routeInfo,
        ServerRequestInterface $request
    ): ResponseInterface {
        $route = new RoutesEnum($routeInfo[1]);
        $controller = $this->controllerLocator->locate($route->getController());
        $this->ensureMethodExists($route, $controller);
        return $controller->{$route->getMethod()}(
            $request,
            isset($routeInfo[2]) ? $routeInfo[2] : []
        );
    }

    private function ensureMethodExists(RoutesEnum $route, Controller $controller)
    {
        if (!method_exists($controller, $route->getMethod())) {
            throw new RuntimeException(
                'Invalid route method: %s for controller %s',
                $route->getMethod(),
                $route->getController()
            );
        }
    }
}

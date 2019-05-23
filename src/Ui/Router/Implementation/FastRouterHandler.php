<?php

namespace Lemundo\Translator\Ui\Router\Implementation;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Lemundo\Translator\Ui\Controllers\Controller;
use Lemundo\Translator\Ui\Router\ControllerLocator;
use Lemundo\Translator\Ui\Router\RequestDispatcher;
use Lemundo\Translator\Ui\Router\RoutesEnum;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Diactoros\Response;

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

    public static function createDispatcherCallable(): callable
    {
        return function (RouteCollector $collector) {
            $collector->addRoute('PUT', '/locale/{locale}/translation', RoutesEnum::SET_TRANSLATION);
            $collector->addRoute('GET', '/locale/{locale}/translation', RoutesEnum::LIST_TRANSLATIONS);
            $collector->addRoute('GET', '/locale/{locale}/translation/{translationId}', RoutesEnum::GET_TRANSLATION);
            $collector->addRoute('DELETE', '/locale/{locale}/translation/{translationId}', RoutesEnum::DELETE_TRANSLATION);
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
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = new Response(
                    sprintf(
                        'Method not allowed. Allowed methods for %s: %s',
                        $uri,
                        implode(', ', $routeInfo[1])
                    ),
                    405
                );
                break;
            case Dispatcher::FOUND:
                $response = $this->execiteRequestInController($routeInfo, $request);
                break;
            default:
                $response = (new Response())->withStatus(500);
        }

        return $response;
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
                sprintf(
                    'Invalid route method: %s for controller %s',
                    $route->getMethod(),
                    $route->getController()
                )
            );
        }
    }
}

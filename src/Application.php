<?php
namespace Lemundo\Translator;

use Lemundo\Translator\Ui\Router\RequestDispatcher;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\Diactoros\Response;
use Lemundo\Translator\Persistence\Pdo\PdoConnection;

class Application
{
    /** @var RequestDispatcher */
    private $dispatcher;
    /** @var PdoConnection */
    private $pdoConnection;

    public function __construct(RequestDispatcher $dispatcher, PdoConnection $pdoConnection)
    {
        $this->dispatcher = $dispatcher;
        $this->pdoConnection = $pdoConnection;
    }

    public function run()
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
        try {
            $this->pdoConnection->connect();
            $response = $this->dispatcher->dispatch($request);
        } catch (\Exception $e) {
            $response = (new Response())->withStatus(500);
            $response->getBody()->write($e->__toString());
        }
        (new SapiEmitter())->emit($response);
    }
}

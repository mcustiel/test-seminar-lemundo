<?php
namespace Lemundo\Translator\Ui\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RequestDispatcher
{
    public function dispatch(ServerRequestInterface $request): ResponseInterface;
}

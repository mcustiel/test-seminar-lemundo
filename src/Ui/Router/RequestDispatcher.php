<?php

namespace Lemundo\Translator\Ui\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RequestDispatcher
{
    public function dispatch(ServerRequestInterface $request): ResponseInterface;
}

<?php

namespace Lemundo\Translator\Ui\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Lemundo\Translator\Domain\Locale;

class LocalesController extends Controller
{
    public function list(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        var_export('tomato');
        return $this->createJsonResponse(Locale::getLocales());
    }
}

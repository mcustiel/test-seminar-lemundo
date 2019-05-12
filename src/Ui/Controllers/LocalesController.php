<?php

namespace Lemundo\Translator\Ui\Controllers;

use Lemundo\Translator\Domain\Locale;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LocalesController extends Controller
{
    public function list(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        var_export('tomato');

        return $this->createJsonResponse(Locale::getLocales());
    }
}

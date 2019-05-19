<?php

namespace Lemundo\Translator\Ui\Controllers;

use Exception;
use Lemundo\Translator\Persistence\Translations;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

abstract class Controller
{
    /** @var Translations */
    private $persistence;

    public function __construct(Translations $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @param mixed $data
     *
     * @return ResponseInterface
     */
    public function createJsonResponse($data, int $status = 200): ResponseInterface
    {
        $response = (new Response())
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }

    public function createJsonResponseFromException(Exception $e, int $status = 200): ResponseInterface
    {
        $response = (new Response())
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
        $data = [
            'errorMessage' => $e->getMessage(),
            'errorInfo'    => $e->__toString(),
        ];
        $response->getBody()->write(json_encode($data));

        return $response;
    }

    protected function getPersistence(): Translations
    {
        return $this->persistence;
    }
}

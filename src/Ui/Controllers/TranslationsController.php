<?php

namespace Lemundo\Translator\Ui\Controllers;

use Lemundo\Translator\Domain\Locale;
use Lemundo\Translator\Domain\Text;
use Lemundo\Translator\Domain\TranslationId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class TranslationsController extends Controller
{
    const FORCE_ARRAY = true;

    public function list(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $map = $this->getPersistence()->getTranslations(new Locale($arguments['locale']));
        $responseMap = [];
        /** @var \Lemundo\Translator\Domain\TranslationId $translationId */
        /** @var \Lemundo\Translator\Domain\Text $text */
        foreach ($map as $translationId => $text) {
            $responseMap[$translationId->asString()] = $text->asString();
        }

        return $this->createJsonResponse($responseMap);
    }

    public function add(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        try {
            $body = $request->getBody()->__toString();
            $translationData = json_decode($body, self::FORCE_ARRAY);
            $translationId = new TranslationId($translationData['id']);
            $text = new Text($translationData['text']);
            $locale = new Locale($arguments['locale']);

            $this->getPersistence()->add(
                $translationId,
                $locale,
                $text
            );

            return $this->createJsonResponse(
                    [
                        'id'     => $translationId->asString(),
                        'locale' => $locale->asString(),
                        'text'   => $text->asString(),
                    ],
                201
            );
        } catch (\RuntimeException $e) {
            $response = (new Response())->withStatus(500);
            $response->getBody()->write($e->__toString());

            return $response;
        }
    }

    public function get(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        try {
            $text = $this->getPersistence()->get(
                new TranslationId($arguments['translationId']),
                new Locale($arguments['locale'])
            );

            return $this->createJsonResponse($text->asString());
        } catch (\RuntimeException $e) {
            return (new Response())->withStatus(404);
        }
    }
}

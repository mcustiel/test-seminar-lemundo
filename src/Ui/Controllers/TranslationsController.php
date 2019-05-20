<?php

namespace Lemundo\Translator\Ui\Controllers;

use Lemundo\Translator\Domain\Locale;
use Lemundo\Translator\Domain\Text;
use Lemundo\Translator\Domain\TranslationId;
use Lemundo\Translator\Persistence\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class TranslationsController extends Controller
{
    const FORCE_ARRAY = true;

    public function list(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        try {
            $map = $this->getPersistence()->getTranslations(new Locale($arguments['locale']));
            $responseMap = [];
            /** @var \Lemundo\Translator\Domain\TranslationId $translationId */
            /** @var \Lemundo\Translator\Domain\Text $text */
            foreach ($map as $translationId => $text) {
                $responseMap[$translationId->asString()] = $text->asString();
            }

            return $this->createJsonResponse($responseMap);
        } catch (\InvalidArgumentException $e) {
            $response = (new Response())->withStatus(400);
            $response->getBody()->write($e->getMessage());

            return $response;
        }
    }

    public function set(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        try {
            $translationData = $this->getJsonBodyAsArray($request);
            $this->validateJson($translationData);
            $translationId = new TranslationId($translationData['id']);
            $text = new Text($translationData['text']);
            $locale = new Locale($arguments['locale']);

            $this->getPersistence()->set(
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
        } catch (\InvalidArgumentException $e) {
            $response = (new Response())->withStatus(400);
            $response->getBody()->write($e->getMessage());

            return $response;
        } catch (\Exception $e) {
            return $this->createJsonResponseFromException($e);
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
        } catch (NotFoundException $e) {
            $response = (new Response())->withStatus(404);
            $response->getBody()->write('No translation found');

            return $response;
        } catch (\InvalidArgumentException $e) {
            $response = (new Response())->withStatus(400);
            $response->getBody()->write($e->getMessage());

            return $response;
        } catch (\Exception $e) {
            return $this->createJsonResponseFromException($e);
        }
    }

    public function delete(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        try {
            $this->getPersistence()->delete(
                new TranslationId($arguments['translationId']),
                new Locale($arguments['locale'])
            );

            return (new Response())->withStatus(200);
        } catch (\InvalidArgumentException $e) {
            $response = (new Response())->withStatus(400);
            $response->getBody()->write($e->getMessage());

            return $response;
        } catch (\Exception $e) {
            return $this->createJsonResponseFromException($e);
        }
    }

    private function getJsonBodyAsArray(ServerRequestInterface $request): array
    {
        $body = $request->getBody()->__toString();
        $translationData = json_decode($body, self::FORCE_ARRAY);
        if (!\is_array($translationData)) {
            throw new \InvalidArgumentException(
                'Invalid JSON received. Expecting a hash with keys id and text. Got: ' . var_export($translationData, true)
            );
        }

        return $translationData;
    }

    private function validateJson(array $translationData): void
    {
        $expected = ['id', 'text'];
        $received = array_keys($translationData);
        if (!empty(array_diff($expected, $received))) {
            throw new \InvalidArgumentException(
                'Invalid fields received in JSON. Expecting only: id and text. Got extra: ' .
                json_encode(array_diff($expected, $received))
            );
        }
        if (\count(array_intersect($expected, $received)) !== \count($received)) {
            throw new \InvalidArgumentException(
                'Missing fields in JSON. Expecting: id and text. Got: ' . json_encode($received)
            );
        }
    }
}

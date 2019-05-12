<?php

namespace Lemundo\Translator\Persistence\Pdo;

use Lemundo\Translator\Domain\{TranslationId, Text, Locale};
use Lemundo\Translator\Domain\Collections\TranslationIdTextMap;
use Lemundo\Translator\Persistence\Translations;

class PdoTranslations implements Translations
{
    /** @var PdoConnection */
    private $connection;

    public function __construct(PdoConnection $connection)
    {
        $this->connection = $connection;
    }

    public function add(TranslationId $translationId, Locale $locale, Text $text): void
    {
        $this->connection->insert(
            'translations',
            [
                'translation_id' => $translationId->asString(),
                'locale' => $locale->asString(),
                'text' => $text->asString(),
            ]
        );
    }

    public function get(TranslationId $translationId, Locale $locale): Text
    {
        $row = $this->connection->queryOne(
            'SELECT text FROM translations WHERE translation_id = ? AND locale = ?',
            [
                $translationId->asString(),
                $locale->asString(),
            ]
        );
        return new Text($row['text']);
    }

    public function getTranslations(Locale $locale): TranslationIdTextMap
    {
        $results = $this->connection->query(
            'SELECT translation_id, text FROM translations WHERE locale = ?',
            [$locale->asString()]
        );
        $translations = new TranslationIdTextMap();
        while ($results->hasNext()) {
            $row = $results->next();
            $translations->set(
                new TranslationId($row['translation_id']),
                new Text($row['text'])
            );
        }
        return $translations;
    }
}

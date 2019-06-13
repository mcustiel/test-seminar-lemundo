<?php

class ApiCest
{
    public function testCreatesTranslation(AcceptanceTester $I) : void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/locale/de_DE/translation', ['text' => 'le tomato', 'id' => 'tomato']);
        $I->seeResponseCodeIs(201);
        $I->seeResponseEquals('{"id":"tomato","locale":"de_DE","text":"le tomato"}');
        $I->seeInDatabase('translations', ['translation_id' => 'tomato', 'locale' => 'de_DE', 'text' => 'le tomato']);
        $I->seeNumRecords(1, 'translations');
    }
}

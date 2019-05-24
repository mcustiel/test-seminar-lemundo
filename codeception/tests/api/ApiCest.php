<?php

class ApiCest
{
    public function testCreatesTranslation(AcceptanceTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/locale/de_DE/translation', ['text' => 'le tomato', 'id' => 'tomato']);
        $I->seeResponseCodeIs(201);
        $I->seeResponseEquals('{"id":"tomato","locale":"de_DE","text":"le tomato"}');
    }
}

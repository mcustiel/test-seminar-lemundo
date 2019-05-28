<?php

class UiCest
{
    public function testIndexPageWorks(AcceptanceTester $I): void
    {
        $I->amOnPage('/index.html');
        $I->seeInTitle('Set translation values');
    }
}

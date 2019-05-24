<?php

class UiCest
{
    public function testIndexPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/index.html');
        $I->seeInTitle('Set translation values');
    }
}

<?php

class UiCest
{
    private $webGuy;

    public function _before(AcceptanceTester $I)
    {
        // $this->webGuy = new WebGuy();
    }

    public function testIndexPageWorks(AcceptanceTester $I)
    {
        // $I = $this->webGuy;
        $I->amOnPage('/index.html');
        $I->seeInTitle('tomato');
    }

}

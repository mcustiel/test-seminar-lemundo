<?php

namespace Lemundo\Translator\Tests\Domain;

use Lemundo\Translator\Domain\Text;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/** @covers \Lemundo\Translator\Domain\Text */
class TextTest extends TestCase
{
    public function testCreatesAndRetrievesValue()
    {
        $text = new Text('Potato');
        Assert::assertSame('Potato', $text->asString());
    }
}

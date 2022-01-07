<?php 

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ActualiteHelperTest extends TestCase
{
    public function testGetPlainTextActualite()
    {
        $articles = $this->getMockBuilder('\App\Service\ActualiteHelper')->disableOriginalConstructor()->getMock();
        $articles->expects($this->any())
                    ->method("getPlainTextActualite")
                        ->willReturn(array());

    }
}
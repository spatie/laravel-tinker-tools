<?php

namespace Spatie\TinkerTools\Test;

use Error;
use PHPUnit\Framework\TestCase;
use Spatie\TinkerTools\ShortClassNames;

class ShortClassNamesTest extends TestCase
{
    /** @test */
    public function it_can_register_short_name_classes()
    {
        $foundClass = false;

        try {
            \NamespacedClass::getGreeting();

            $foundClass = true;
        } catch (Error $error) {
        }

        $this->assertFalse($foundClass);

        ShortClassNames::register(__DIR__.'/../vendor/composer/autoload_classmap.php');

        $this->assertEquals('Oh, hi Mark', \NamespacedClass::getGreeting());
    }
}

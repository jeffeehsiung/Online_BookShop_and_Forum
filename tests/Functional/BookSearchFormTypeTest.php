<?php

namespace App\Tests\Functional;

use Symfony\Component\Panther\PantherTestCase;

class BookSearchFormTypeTest extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}

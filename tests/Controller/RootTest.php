<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RootTest extends WebTestCase
{
    public function testItReturnsWelcomeMessageJsonResponse(): void
    {
        $client = self::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString('[]', $client->getResponse()->getContent());
    }
}

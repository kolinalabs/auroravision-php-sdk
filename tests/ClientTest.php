<?php

namespace KolinaLabs\SolarUtils\Auroravision\Tests;

use PHPUnit\Framework\TestCase;
use KolinaLabs\SolarUtils\AuroraVision\Client;

class ClientTest extends TestCase {
    public function testAuthentication() {
        $client = new Client($_ENV['API_KEY'], $_ENV['API_AUTH'], $_ENV['ENTITY_ID']);

        $token = $client->authenticate();

        $this->assertNotNull($token);
    }
}

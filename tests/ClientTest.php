<?php

namespace KolinaLabs\SolarUtils\Auroravision\Tests;

use PHPUnit\Framework\TestCase;
use KolinaLabs\SolarUtils\AuroraVision\Client;

class ClientTest extends TestCase {
    public function testAuthentication() {
        $client = $this->createClient();

        $token = $client->authenticate();

        $this->assertNotNull($token);
    }

    public function testRequestPlantInfo() {
        $client = $this->createClient();

        $data = $client->getPlantInfo();

        $this->assertTrue(is_array($data));
    }

    private function createClient() {
        return Client::create($_ENV['API_KEY'], $_ENV['API_AUTH'], $_ENV['ENTITY_ID']);
    }
}

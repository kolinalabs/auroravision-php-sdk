<?php

namespace KolinaLabs\SolarUtils\Auroravision\Tests;

use PHPUnit\Framework\TestCase;
use KolinaLabs\SolarUtils\AuroraVision\Client;
use KolinaLabs\SolarUtils\AuroraVision\GenerationPower;

class GenerationPowerTest extends TestCase {
    public function testFluentSetterModifyProperties() {
        $generationQuery = GenerationPower::average();

        $modifiers = [
            'timeZone' => 'America/Sao_Paulo',
            'sampleSize' => 'Year',
            'startDate' => new \DateTime('2 months ago'),
            'endDate' => new \DateTime('m')
        ];

        foreach ($modifiers as $property => $value) {
            $setter = 'set' . ucfirst($property);
            $getter = 'get' . ucfirst($property);

            $this->assertEquals(
                $value,
                $generationQuery->$setter($value)->$getter()
            );
        }
    }

    public function testUriFormatter() {
        $generationQuery = GenerationPower::average();
        $this->assertEquals("v1/stats/power/timeseries/123456/GenerationPower/average", $generationQuery->getUri('123456'));
    }

    /**
     * Request API
     */
    public function testIntegrationWithClientRequest() {
        $generationQuery = GenerationPower::average();

        $startDate = new \DateTime('2020-01-05T04:00:00');
        $endDate = new \DateTime('2020-02-05T04:00:00');

        $generationQuery
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $query = $generationQuery->getQuery();

        $this->assertArrayHasKey('startDate', $query);
        $this->assertArrayHasKey('endDate', $query);

        $this->assertEquals('20200105', $query['startDate']);
        $this->assertEquals('20200205', $query['endDate']);

        $client = new Client(
            $_ENV['API_KEY'],
            $_ENV['API_AUTH'],
            $_ENV['ENTITY_ID']
        );

        $result = $client->generation($generationQuery);

        $this->assertTrue(is_array($result));
    }
}

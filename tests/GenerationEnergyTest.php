<?php

namespace KolinaLabs\SolarUtils\Auroravision\Tests;

use PHPUnit\Framework\TestCase;
use KolinaLabs\SolarUtils\AuroraVision\Client;
use KolinaLabs\SolarUtils\AuroraVision\GenerationEnergy;

class GenerationEnergyTest extends TestCase {

    public function testDefaultsAndModifiers() {
        $generationEnergy = new GenerationEnergy();

        $this->assertEquals('UTC', $generationEnergy->getTimeZone());
        $this->assertEquals('Month', $generationEnergy->getSampleSize());
        $this->assertInstanceOf(\DateTime::class, $generationEnergy->getStartDate());
        $this->assertInstanceOf(\DateTime::class, $generationEnergy->getEndDate());

        $modifiers = [
            'timeZone' => 'America/Sao_Paulo',
            'sampleSize' => 'Year',
            'startDate' => new \DateTime('-1 m'),
            'endDate' => new \DateTime('m')
        ];

        foreach ($modifiers as $property => $value) {
            $setter = 'set' . ucfirst($property);
            $getter = 'get' . ucfirst($property);

            $this->assertEquals(
                $value,
                $generationEnergy->$setter($value)->$getter()
            );
        }
    }

    public function testIntegrationHandler() {
        $generationEnergy = new GenerationEnergy();

        $startDate = new \DateTime('2020-01-05T04:00:00');
        $endDate = new \DateTime('2020-02-05T04:00:00');

        $generationEnergy
            ->setStartDate($startDate)
            ->setEndDate($endDate)
        ;

        $query = $generationEnergy->getQuery();

        $this->assertArrayHasKey('startDate', $query);
        $this->assertArrayHasKey('endDate', $query);

        $this->assertEquals('20200105', $query['startDate']);
        $this->assertEquals('20200205', $query['endDate']);

        $this->assertEquals("v1/stats/energy/timeseries/123456/GenerationEnergy/delta", $generationEnergy->getUri('123456'));

        $client = new Client(
            $_ENV['API_KEY'],
            $_ENV['API_AUTH'],
            $_ENV['ENTITY_ID']
        );

        $result = $client->handle($generationEnergy);

        $this->assertTrue(is_array($result));
    }
}

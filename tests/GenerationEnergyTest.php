<?php

namespace KolinaLabs\SolarUtils\Auroravision\Tests;

use PHPUnit\Framework\TestCase;
use KolinaLabs\SolarUtils\AuroraVision\Client;
use KolinaLabs\SolarUtils\AuroraVision\GenerationEnergy;

class GenerationEnergyTest extends TestCase
{
    public function testDefaultInstanceProperties()
    {
        $generationQuery = GenerationEnergy::delta();

        $this->assertEquals('UTC', $generationQuery->getTimeZone());
        $this->assertEquals('Month', $generationQuery->getSampleSize());
        $this->assertInstanceOf(\DateTime::class, $generationQuery->getStartDate());
        $this->assertInstanceOf(\DateTime::class, $generationQuery->getEndDate());
    }
    
    public function testFluentSetterModifyProperties()
    {
        $generationQuery = GenerationEnergy::delta();

        $modifiers = [
            'timeZone' => 'America/Sao_Paulo',
            'sampleSize' => 'Year',
            'startDate' => new \DateTime('1 year ago'),
            'endDate' => new \DateTime()
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

    public function testUriFormatter()
    {
        $generationQuery = GenerationEnergy::delta();
        
        $expectedUri = 'v1/stats/energy/timeseries/123456/GenerationEnergy/delta';

        $this->assertEquals($expectedUri, $generationQuery->getUri('123456'));
    }

    /**
     * Request API
     */
    public function testIntegrationWithClientRequest()
    {
        $generationQuery = GenerationEnergy::delta();

        $startDate = new \DateTime('2020-01-05T04:00:00');
        $endDate = new \DateTime('2020-02-05T04:00:00');

        $generationQuery
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setSampleSize(GenerationEnergy::SAMPLE_SIZE_HOUR)
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

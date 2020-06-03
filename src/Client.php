<?php

/*
 * This file is part of the KolinaLabs/SolarUtils project.
 *
 * (c) Claudinei Machado <claudinei@kolinalabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KolinaLabs\SolarUtils\AuroraVision;

/**
 * Client provides utility methods for api authentication
 * and handle request from resources
 *
 * @author Claudinei Machado <cjchamado@gmail.com>
 */
class Client extends \GuzzleHttp\Client
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiAuth;

    /**
     * @var string
     */
    private $identity;

    private $defaultOptions = [
        'base_uri' => 'https://api.auroravision.net/api/rest/'
    ];

    /**
     * @param string $apiKey (eg: abcde-12345-fghij-67890)
     * @param string $apiAuth (eg: "Basic abcde12345=")
     * @param string $identity (eg: 1543210)
     */
    public function __construct(string $apiKey, string $apiAuth, string $identity, array $options = [])
    {
        $this->apiKey = $apiKey;
        $this->apiAuth = $apiAuth;
        $this->identity = $identity;

        parent::__construct(array_merge($this->defaultOptions, $options));
    }

    /**
     * Autheticate with credentials
     */
    public function authenticate()
    {
        $response = $this->request('GET', 'authenticate', [
            'headers' => [
                'X-AuroraVision-ApiKey' => $this->apiKey,
                'Authorization' => $this->apiAuth
            ]
        ]);

        try {
            $contents = json_decode($response->getBody()->getContents(), true);
            return $contents['result'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the plant information
     */
    public function getPlantInfo()
    {
        $response = $this->request('GET', sprintf('v1/plant/%s/billingData', $this->identity), [
            'headers' => [
                'X-AuroraVision-Token' => $this->authenticate()
            ]
        ]);

        return $this->parseResult($response);
    }

    /**
     * Request generation query
     */
    public function generation(GenerationQuery $generationQuery): array
    {
        $response = $this->request('GET', $generationQuery->getURI($this->identity), [
            'query' => $generationQuery->getQuery(),
            'headers' => [
                'X-AuroraVision-Token' => $this->authenticate()
            ]
        ]);

        return $this->parseResult($response);
    }

    /**
     * @return Client
     */
    public static function create(string $apiKey, string $apiAuth, string $identity, array $options = []): self
    {
        return new self($apiKey, $apiAuth, $identity, $options);
    }

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     * @return array
     */
    private function parseResult(\GuzzleHttp\Psr7\Response $response): array
    {
        $data = json_decode($response->getBody()->getContents(), true);
        return $data['result'];
    }
}

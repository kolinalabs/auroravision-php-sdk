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
class Client extends \GuzzleHttp\Client {
    private $defaultOptions = [
        'base_uri' => 'https://api.auroravision.net/api/rest/'
    ];

    function __construct(string $apiKey, string $basicAuth, string $entityId, array $options = []) {
        $this->apiKey = $apiKey;
        $this->basicAuth = $basicAuth;
        $this->entityId = $entityId;

        parent::__construct(array_merge($this->defaultOptions, $options));
    }

    public function authenticate() {
        $response = $this->request('GET', 'authenticate', [
            'headers' => [
                'X-AuroraVision-ApiKey' => $this->apiKey,
                'Authorization' => $this->basicAuth
            ]
        ]);

        try {
            $contents = json_decode($response->getBody()->getContents(), true);
            return $contents['result'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function handle($source): array {
        $response = $this->request('GET', $source->getURI($this->entityId), [
            'query' => $source->getQuery(),
            'headers' => [
                'X-AuroraVision-Token' => $this->authenticate()
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['result'];
    }
}

<?php

namespace Scopus;

use GuzzleHttp\Client;

class ScopusApiFactory
{
    const TIMEOUT_DEFAULT = 40;

    private $apiKey;
    private $institutionToken;
    private $timeout;

    public function __construct(string $apiKey, string $institutionToken = null, int $timeout = self::TIMEOUT_DEFAULT)
    {
        $this->apiKey = $apiKey;
        $this->institutionToken = $institutionToken;
        $this->timeout = $timeout;
    }

    public function createApiClient(): ScopusApi
    {
        $headers = [
            'Accept' => 'application/json',
            'X-ELS-APIKey' => $this->apiKey,
        ];

        if ($this->institutionToken !== null) {
            $headers['X-ELS-Insttoken'] = $this->institutionToken;
        }

        return new ScopusApi(
            new Client([
                'timeout' => $this->timeout,
                'headers' => $headers,
            ])
        );
    }
}

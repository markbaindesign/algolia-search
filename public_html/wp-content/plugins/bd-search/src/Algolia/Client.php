<?php

namespace BD324\Search\Algolia;

use Algolia\AlgoliaSearch\SearchClient;

class Client
{
    private static $instance = null;
    private $client;

    private function __construct()
    {
        if (!defined('ALGOLIA_APPLICATION_ID') || !defined('ALGOLIA_API_KEY')) {
            throw new \RuntimeException('ALGOLIA_APPLICATION_ID or ALGOLIA_API_KEY is not defined.');
        }
        $this->client = SearchClient::create(ALGOLIA_APPLICATION_ID, ALGOLIA_API_KEY);
    }

    public static function get_instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_client()
    {
        return $this->client;
    }

    public function get_index(string $index_name)
    {
        return $this->client->initIndex($index_name);
    }
}

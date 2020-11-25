<?php declare(strict_types = 1);

namespace Joking\Infrastructure\Persistence\Redis;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisClientFactory
{
    public static function createRedisClient(string $databaseHost): CategoryRepository
    {
        $client = RedisAdapter::createConnection('redis://'.$databaseHost);
        $client->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_JSON);

        return new CategoryRepository($client);
    }
}

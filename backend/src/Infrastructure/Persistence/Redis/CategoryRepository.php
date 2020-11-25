<?php declare(strict_types = 1);

namespace Joking\Infrastructure\Persistence\Redis;

use Joking\Domain\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    private const CACHE_KEY = 'categories';
    private \Redis $redisClient;

    public function __construct(\Redis $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->redisClient->get(self::CACHE_KEY);
    }

    /**
     * @inheritDoc
     */
    public function store(array $categories): void
    {
        $success = $this->redisClient->set(self::CACHE_KEY, $categories);

        if (!$success) {
            throw new \RuntimeException('Could not perform the store command.');
        }
    }
}

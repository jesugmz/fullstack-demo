<?php declare(strict_types = 1);

namespace Joking\Application;

use Joking\Domain\Joke;

class WordSearchService
{
    private JokeClientInterface $jokeClient;

    public function __construct(JokeClientInterface $jokeClient)
    {
        $this->jokeClient = $jokeClient;
    }

    /**
     * Get a list of Joke by the given word.
     *
     * @param string $word
     *
     * @return Joke[]
     */
    public function process(string $word): array
    {
        // For simplicity we do not convert "word" into a complex domain object
        // - not enough domain requirements to add such a extra complexity.
        return $this->jokeClient->fetchByWord($word);
    }
}

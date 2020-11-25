<?php declare(strict_types = 1);

namespace Joking\Application;

use Joking\Domain\Joke;

class RandomSearchService
{
    private JokeClientInterface $jokeClient;
    private MailClientInterface $mailClient;

    public function __construct(JokeClientInterface $jokeClient, MailClientInterface $mailClient)
    {
        $this->jokeClient = $jokeClient;
        $this->mailClient = $mailClient;
    }

    /**
     * Get a Joke by the given word.
     *
     * @return Joke
     */
    public function process(): Joke
    {
        // We could catch here any kind of client issue and apply retry policies
        // if apply. Not implemented for simplicity.
        return $this->jokeClient->fetchRandom();
    }
}

<?php declare(strict_types = 1);

namespace Joking\Infrastructure\Gateway;

use Joking\Application\JokeClientInterface;
use Joking\Domain\Joke;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChuckNorrisClient implements JokeClientInterface
{
    const URL_BASE = 'https://api.chucknorris.io/jokes/';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function fetchByWord(string $word): array
    {
        $content = $this->fetch(
            sprintf(self::URL_BASE.'search?query=%s', $word)
        );

        $jokes = array();
        foreach ($content['result'] as $result) {
            $jokes []= $this->arrayToJoke($result);
        }

        return $jokes;
    }

    /**
     * @inheritDoc
     */
    public function fetchByCategory(string $category): array
    {
        // Since the external service does not implement Joke search by category
        // we use the standard free term search and later filter by category.
        // Hack to retrieve - I guess - all the jokes.
        $jokes = $this->fetchByWord('chuck');
        return array_values(
            array_filter($jokes, function ($joke) use ($category) {
                return $joke->category() === $category;
        }));
    }

    /**
     * @inheritDoc
     */
    public function fetchCategories(): array
    {
        return $this->fetch(self::URL_BASE.'categories');
    }

    /**
     * @inheritDoc
     */
    public function fetchRandom(): Joke
    {
        $content = $this->fetch(self::URL_BASE.'random');
        return $this->arrayToJoke($content);
    }

    /**
     * Fetches Jokes from the given endpoint.
     *
     * @param string $endpoint
     *
     * @return Joke|Joke[]
     *
     * @throws \RuntimeException if cannot access the external service or the given data is not the expected.
     * @throws \DomainException if the given data is wrong.
     */
    private function fetch(string $endpoint)
    {
        try {
            $response = $this->client->request(
                'GET',
                $endpoint,
                ['timeout' => 5]
            );
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                sprintf('Could not perform the request: %s', $e->getMessage())
            );
        }

        try {
            if (Response::HTTP_OK != $response->getStatusCode()) {
                throw new \DomainException(
                    sprintf('Bad status code: %s', $response->getStatusCode())
                );
            }
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException(
                sprintf('Could not access to the status code: %s', $e->getMessage())
            );
        }

        try {
            return json_decode($response->getContent(), true);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            throw new \RuntimeException(
                sprintf('Could not access to the content: %s', $e->getMessage())
            );
        }
    }

    /**
     * Converts primitive array into domain Joke.
     *
     * @param array $joke
     *
     * @return Joke
     *
     * @throws \RuntimeException if the given raw joke does not contain the expected fields.
     */
    private function arrayToJoke(array $joke): Joke
    {
        try {
            // Although the external service result gives a list seems to be always one.
            // I assume for our business domain we use just this first category.
            return Joke::createFromRaw($joke['id'], $joke['url'], $joke['value'], current($joke['categories']) ?: '');
        } catch (\OutOfBoundsException $e) {
            throw new \RuntimeException(
                sprintf('Could not access to some required attributes (id, url, value, categories): %s', array_keys($joke))
            );
        }
    }
}

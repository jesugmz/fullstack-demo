<?php declare(strict_types = 1);

namespace Joking\Application;

use Joking\Domain\Joke;

interface JokeClientInterface
{
    /**
     * Fetches a list of Joke by the given word.
     *
     * @param string $word
     *
     * @return Joke[]
     */
    public function fetchByWord(string $word): array;

    /**
     * Fetches a Joke by the given category.
     *
     * @param string $category
     *
     * @return Joke[]
     */
    public function fetchByCategory(string $category): array;

    /**
     * Fetches the list of categories.
     *
     * @return string[]
     */
    public function fetchCategories(): array;

    /**
     * Fetches a Joke randomly.
     *
     * @return Joke
     */
    public function fetchRandom(): Joke;
}

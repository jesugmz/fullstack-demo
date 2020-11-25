<?php declare(strict_types = 1);

namespace Joking\Application;

use Joking\Domain\Joke;

class CategorySearchService
{
    private JokeClientInterface $jokeClient;

    public function __construct(JokeClientInterface $jokeClient)
    {
        $this->jokeClient = $jokeClient;
    }

    /**
     * Get a list of Joke by the given word.
     *
     * @param string $category
     *
     * @return Joke[]
     *
     * @throws \DomainException if not valid category is given.
     */
    public function process(string $category): array
    {
        if ($this->isValidCategory($category)) {
            // For simplicity we do not convert "category" into a complex domain
            // object - not enough domain requirements to add such a extra complexity.
            // Deal with the category like a word
            return $this->jokeClient->fetchByCategory($category);
        }

        throw new \DomainException('Not valid category given.');
    }

    /**
     * Validates if the given category is, or not, a valid category.
     *
     * @param string $category
     *
     * @return bool
     */
    private function isValidCategory(string $category)
    {
        return in_array($category, $this->jokeClient->fetchCategories());
    }
}

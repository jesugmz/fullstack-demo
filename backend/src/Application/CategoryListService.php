<?php declare(strict_types = 1);

namespace Joking\Application;

use Joking\Domain\CategoryRepositoryInterface;

class CategoryListService
{
    private JokeClientInterface $jokeClient;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(JokeClientInterface $jokeClient, CategoryRepositoryInterface $categoryRepository)
    {
        $this->jokeClient = $jokeClient;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get a list of categories.
     *
     * @return string[]
     */
    public function process(): array
    {
        $cachedCategories = $this->categoryRepository->get();

        if ($cachedCategories !== false) {
            return $cachedCategories;
        }

        $categories = $this->jokeClient->fetchCategories();
        $this->categoryRepository->store($categories);

        return $categories;
    }
}

<?php declare(strict_types = 1);

namespace Joking\Domain;

interface CategoryRepositoryInterface
{
    /**
     * Gets the list of categories from persistence.
     */
    public function get();

    /**
     * Stores the given category list into persistence.
     *
     * @param array $categories
     *
     * @throws \RuntimeException if cannot perform the command.
     */
    public function store(array $categories): void;
}

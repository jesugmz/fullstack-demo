<?php declare(strict_types = 1);

namespace Joking\Domain;

class Joke implements \JsonSerializable
{
    public string $id;
    public string $url;
    public string $value;
    // Although the external service result gives a list seems to be always one.
    // I assume for our business domain we use just this first category.
    public string $category;

    /**
     * Forces specific constructors to ensure data consistency.
     *
     * @param string $id
     * @param string $url
     * @param string $value
     * @param string $category
     */
    private function __construct(string $id, string $url, string $value, string $category = '')
    {
        $this->id = $id;
        $this->url = $url;
        $this->value = $value;
        $this->category = $category;
    }

    /**
     * Creates a Joke from raw primitives.
     *
     * @param string $id
     * @param string $url
     * @param string $value
     * @param string $category
     *
     * @return Joke
     */
    public static function createFromRaw(string $id, string $url, string $value, string $category = ''): Joke
    {
        return new self($id, $url, $value, $category);
    }

    /**
     * Gets the attribute category.
     *
     * @return string
     */
    public function category(): string
    {
        return $this->category;
    }

    /**
     * Serialize the object to raw primitives for json_encode built-in function
     * accessing its private properties.
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
